<?php

declare(strict_types=1);

namespace App\Support\SystemOverview\Probes;

use App\Models\CompanySetting;
use App\Models\User;
use App\Support\SystemOverview\ProbeCost;
use App\Support\SystemOverview\ProbeItem;
use App\Support\SystemOverview\ProbeStatus;
use App\Support\SystemOverview\SystemProbe;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Number;
use Laravel\Fortify\Features;

/**
 * The configuration a reviewer would ask about before trusting this install.
 *
 * Every line is a verdict, not a measurement: the probe reads configuration, two
 * company settings, the privileged accounts, and a handful of paths. Deliberately
 * nothing here depends on who is asking, because sections are cached under one key
 * with no user dimension; a per-viewer verdict would be served to the next reader.
 */
final class PostureProbe implements SystemProbe
{
    /**
     * @var list<string> Roles whose accounts are expected to hold a second factor.
     */
    private const PRIVILEGED_ROLES = ['Super Admin', 'Admin'];

    /**
     * @var list<string> Catch-all proxy patterns, any of which trusts every client.
     */
    private const TRUST_ALL_PROXIES = ['*', '**', '0.0.0.0/0', '::/0'];

    public function key(): string
    {
        return 'posture';
    }

    public function label(): string
    {
        return 'Security Posture';
    }

    public function description(): string
    {
        return 'Configuration, exposure, and the accounts that can sign in.';
    }

    public function icon(): string
    {
        return 'shield-check';
    }

    public function cost(): ProbeCost
    {
        return ProbeCost::Light;
    }

    /**
     * @return list<ProbeItem>
     */
    public function run(): array
    {
        $registrationOpen = $this->setting('enable_registration');
        $verificationRequired = $this->setting('require_email_verification');

        return [
            ProbeItem::flagged(
                'Debug mode',
                config('app.debug') ? 'On' : 'Off',
                config('app.debug') ? ProbeStatus::Warn : ProbeStatus::Ok,
                'Errors would expose stack traces while this is on.',
            ),
            ProbeItem::flagged(
                'Registration',
                $registrationOpen ? 'Open' : 'Closed',
                $registrationOpen ? ProbeStatus::Warn : ProbeStatus::Ok,
                $registrationOpen
                    ? 'Anyone can create an account until this is switched off.'
                    : 'New accounts can only be created from the admin staff screens.',
            )->withRoute('admin.settings.index'),
            ProbeItem::flagged(
                'Email verification',
                $verificationRequired ? 'Required' : 'Optional',
                $verificationRequired ? ProbeStatus::Ok : ProbeStatus::Warn,
                'Verified middleware is skipped while this is optional.',
            )->withRoute('admin.settings.index'),
            $this->secondFactors(),
            $this->twoFactorCoverage(),
            ProbeItem::flagged(
                'Application key',
                $this->hasKey() ? 'Set' : 'Missing',
                $this->hasKey() ? ProbeStatus::Ok : ProbeStatus::Fail,
                'Without it, cookies, signed URLs, and encrypted settings cannot be read.',
            ),
            $this->writablePaths(),
            $this->freeSpace(),
            $this->proxyTrust(),
            $this->envExposure(),
            $this->viteMarker(),
            $this->auditTrail(),
        ];
    }

    /**
     * Which second factors Fortify offers at all.
     */
    private function secondFactors(): ProbeItem
    {
        $twoFactor = Features::canManageTwoFactorAuthentication();
        $passkeys = Features::canManagePasskeys();

        if (! $twoFactor && ! $passkeys) {
            return ProbeItem::flagged(
                'Sign-in hardening',
                'None offered',
                ProbeStatus::Warn,
                'config/fortify.php registers neither two-factor authentication nor passkeys.',
            );
        }

        $offered = array_filter([
            $twoFactor ? 'two-factor' : null,
            $passkeys ? 'passkeys' : null,
        ]);

        return ProbeItem::flagged(
            'Sign-in hardening',
            implode(' and ', $offered).' available',
            count($offered) === 2 ? ProbeStatus::Ok : ProbeStatus::Info,
            'Users may enable these on their profile, they are not enforced.',
        );
    }

    /**
     * How many privileged accounts actually hold a confirmed second factor.
     */
    private function twoFactorCoverage(): ProbeItem
    {
        $privileged = User::query()->whereHas(
            'roles',
            fn ($query): mixed => $query->whereIn('name', self::PRIVILEGED_ROLES),
        );

        $total = (clone $privileged)->count();
        $uncovered = $privileged->whereNull('two_factor_confirmed_at')->count();

        if ($total === 0) {
            return ProbeItem::flagged(
                'Two-factor coverage',
                'No privileged accounts',
                ProbeStatus::Info,
                'Checked against: '.implode(', ', self::PRIVILEGED_ROLES).'.',
            );
        }

        if ($uncovered === 0) {
            return ProbeItem::flagged(
                'Two-factor coverage',
                'All '.$total.' covered',
                ProbeStatus::Ok,
                'Every privileged account confirms a second factor.',
            );
        }

        return ProbeItem::flagged(
            'Two-factor coverage',
            $uncovered.' of '.$total.' uncovered',
            ProbeStatus::Warn,
            'Passwords alone reach the settings, staff, and cache screens.',
        );
    }

    /**
     * @param string $key Company setting key.
     */
    private function setting(string $key): bool
    {
        return filter_var(CompanySetting::get($key, false), FILTER_VALIDATE_BOOLEAN);
    }

    private function hasKey(): bool
    {
        return filled(config('app.key'));
    }

    /**
     * The paths Laravel and this application write to.
     */
    private function writablePaths(): ProbeItem
    {
        $paths = $this->pathList();
        $missing = [];
        $blocked = [];

        foreach ($paths as $label => $path) {
            if (! File::isDirectory($path)) {
                $missing[] = $label;

                continue;
            }

            if (! File::isWritable($path)) {
                $blocked[] = $label;
            }
        }

        if ($missing === [] && $blocked === []) {
            return ProbeItem::flagged(
                'Writable paths',
                'All writable',
                ProbeStatus::Ok,
                count($paths).' checked: upload, cache, view, and log directories.',
            );
        }

        $found = array_merge(
            array_map(fn (string $label): string => $label.' missing', $missing),
            array_map(fn (string $label): string => $label.' not writable', $blocked),
        );

        return ProbeItem::flagged(
            'Writable paths',
            implode(' · ', $found),
            $blocked === [] ? ProbeStatus::Warn : ProbeStatus::Fail,
            'Writes fail there until the web user owns or can write them.',
        );
    }

    /**
     * Labels mapped to absolute paths, narrowed to what this configuration uses.
     *
     * @return array<string, string>
     */
    private function pathList(): array
    {
        $paths = [
            'bootstrap/cache' => base_path('bootstrap/cache'),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/views' => storage_path('framework/views'),
            'storage/logs' => storage_path('logs'),
        ];

        if ((string) config('session.driver') === 'file') {
            $paths['storage/framework/sessions'] = storage_path('framework/sessions');
        }

        // The link and the folder only matter while uploads live on this host.
        if (config('filesystems.disks.public.driver') !== 's3') {
            $paths['storage/app/public'] = storage_path('app/public');
        }

        return $paths;
    }

    /**
     * Volume headroom, read from the same stat call the footprint section uses.
     */
    private function freeSpace(): ProbeItem
    {
        $free = @disk_free_space(base_path());
        $total = @disk_total_space(base_path());

        if (! is_float($free) || ! is_float($total) || $total <= 0.0) {
            return ProbeItem::flagged(
                'Free disk space',
                'Unavailable',
                ProbeStatus::Info,
                'This host does not report volume size.',
            );
        }

        $used = 1 - ($free / $total);

        $status = match (true) {
            $used >= 0.9 => ProbeStatus::Fail,
            $used >= 0.75 => ProbeStatus::Warn,
            default => ProbeStatus::Ok,
        };

        return ProbeItem::flagged(
            'Free disk space',
            Number::fileSize((int) $free, precision: 0).' free',
            $status,
            round($used * 100).'% of the volume is in use.',
        );
    }

    /**
     * Whether any client may assert the address a request came from. Withheld from
     * the export: the ranges describe the internal network.
     */
    private function proxyTrust(): ProbeItem
    {
        $proxies = request()->getTrustedProxies();

        if ($proxies === []) {
            return ProbeItem::flagged(
                'Trusted proxies',
                'None',
                ProbeStatus::Info,
                'X-Forwarded headers are ignored, so behind a proxy every visitor shares one address.',
            )->sensitive();
        }

        if (array_intersect($proxies, self::TRUST_ALL_PROXIES) !== []) {
            return ProbeItem::flagged(
                'Trusted proxies',
                'Every client',
                ProbeStatus::Warn,
                'Any visitor may set X-Forwarded-For, so the address recorded in sessions and audits can be spoofed.',
            )->sensitive();
        }

        return ProbeItem::flagged(
            'Trusted proxies',
            count($proxies).' pinned',
            ProbeStatus::Ok,
            'Only the configured ranges may set forwarded headers.',
        )->sensitive();
    }

    /**
     * A .env inside the document root is readable wherever the web server does not
     * block dotfiles, which is how Laragon serves this project.
     */
    private function envExposure(): ProbeItem
    {
        $exposed = File::exists(public_path('.env'));

        return ProbeItem::flagged(
            'Env file',
            $exposed ? 'Inside public/' : 'Outside the document root',
            $exposed ? ProbeStatus::Fail : ProbeStatus::Ok,
            $exposed ? 'Move it above public/ and reload the configuration.' : 'Only the running process reads it.',
        );
    }

    /**
     * The marker Vite writes while the dev server runs; a stale one makes every
     * asset URL point at a port that is no longer listening.
     */
    private function viteMarker(): ProbeItem
    {
        $present = File::exists(public_path('hot'));

        return ProbeItem::flagged(
            'Vite dev marker',
            $present ? 'public/hot present' : 'Absent',
            $present ? ProbeStatus::Info : ProbeStatus::Ok,
            $present
                ? 'Assets resolve to the dev server. Delete public/hot when npm run dev is not serving this app.'
                : 'Assets resolve to the build in public/build.',
        );
    }

    /**
     * Whether the audit trail this page writes into is switched on.
     */
    private function auditTrail(): ProbeItem
    {
        $tables = Schema::hasTable('audit_activity_logs') && Schema::hasTable('audit_action_logs');

        if (! $tables) {
            return ProbeItem::flagged(
                'Audit trail',
                'Tables missing',
                ProbeStatus::Fail,
                'Run the auditify migrations before anything can be recorded.',
            );
        }

        return ProbeItem::flagged(
            'Audit trail',
            config('auditify.auto_audit_models') ? 'Model changes recorded' : 'Model changes not recorded',
            config('auditify.auto_audit_models') ? ProbeStatus::Ok : ProbeStatus::Warn,
            'Activity and security logs are written either way.',
        );
    }
}
