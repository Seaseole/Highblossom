<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Render the admin sidebar with theme toggle, mobile menu, and company info.
 */
final class AdminSidebar extends Component
{
    public string $theme = 'auto';

    public bool $mobileMenuOpen = false;

    public int $userCount = 0;

    public ?string $logoUrl = null;

    public ?string $companyName = null;

    /**
     * Initialize the sidebar with the current user's theme and company settings.
     */
    public function mount(): void
    {
        $this->theme = Auth::check() ? Auth::user()->theme?->value ?? 'auto' : 'auto';
        $this->mobileMenuOpen = false;
        $this->userCount = User::count();
        $this->logoUrl = CompanySetting::get('business_logo') ? Storage::url(CompanySetting::get('business_logo')) : null;
        $this->companyName = CompanySetting::get('company_name', config('app.name'));
    }

    #[On('toggle-sidebar')]
    /**
     * Toggle the mobile sidebar menu open/closed.
     */
    public function toggleMobileMenu(): void
    {
        $this->mobileMenuOpen = ! $this->mobileMenuOpen;
    }

    /**
     * Cycle through or set a specific theme (auto, light, dark).
     */
    public function toggleTheme(?string $newTheme = null): void
    {
        if ($newTheme) {
            $this->setTheme($newTheme);
        } else {
            // Cycle through: auto -> light -> dark -> auto
            $newTheme = match ($this->theme) {
                'auto' => 'light',
                'light' => 'dark',
                'dark' => 'auto',
                default => 'auto',
            };
            $this->setTheme($newTheme);
        }
    }

    /**
     * Persist the theme to the database and apply it via JavaScript.
     */
    private function setTheme(string $theme): void
    {
        $this->theme = $theme;

        if (Auth::check()) {
            Auth::user()->update([
                'theme' => $this->theme,
            ]);
        }

        $this->dispatch('theme-updated', theme: $this->theme);

        $this->js(<<<JS
            const theme = '{$this->theme}';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (theme === 'auto' && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            localStorage.setItem('theme', theme);
            localStorage.setItem('flux.appearance', theme === 'auto' ? 'system' : theme);
        JS);
    }

    /**
     * Determine whether the effective appearance is dark mode.
     */
    public function isEffectiveDark(): bool
    {
        return match ($this->theme) {
            'dark' => true,
            'light' => false,
            default => request()->hasHeader('Sec-CH-UA-Platform')
                ? false // Server-side fallback
                : true, // Will be resolved client-side
        };
    }

    /**
     * Close the mobile sidebar menu.
     */
    public function closeMobileMenu(): void
    {
        $this->mobileMenuOpen = false;
    }

    /**
     * Open the mobile sidebar menu.
     */
    public function openMobileMenu(): void
    {
        $this->mobileMenuOpen = true;
    }

    /**
     * Render the admin sidebar component.
     */
    public function render(): View
    {
        return view('livewire.admin-sidebar');
    }
}
