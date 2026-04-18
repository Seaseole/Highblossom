<?php

use App\Domains\Content\Models\SeoStaticRoute;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

new class extends Component {
    use WithPagination;

    public array $routes = [];
    public ?int $editingId = null;
    public array $form = [];

    public function mount(): void
    {
        $this->loadRoutes();
    }

    public function loadRoutes(): void
    {
        $routeNames = config('seo.static_routes', []);
        $existing = SeoStaticRoute::all()->keyBy('route_name');

        $this->routes = collect($routeNames)->map(function ($routeName) use ($existing) {
            $seoRoute = $existing->get($routeName);

            return [
                'route_name' => $routeName,
                'route_label' => $this->getRouteLabel($routeName),
                'exists' => $seoRoute !== null,
                'id' => $seoRoute?->id,
                'meta_title' => $seoRoute?->meta_title ?? '',
                'meta_description' => $seoRoute?->meta_description ?? '',
                'no_index' => $seoRoute?->no_index ?? false,
                'priority' => $seoRoute?->priority ?? 0.5,
                'changefreq' => $seoRoute?->changefreq ?? 'monthly',
            ];
        })->toArray();
    }

    public function edit(int $index): void
    {
        $route = $this->routes[$index];
        $this->editingId = $index;

        $seoRoute = $route['id'] ? SeoStaticRoute::find($route['id']) : null;

        $this->form = [
            'route_name' => $route['route_name'],
            'meta_title' => $seoRoute?->meta_title ?? '',
            'meta_description' => $seoRoute?->meta_description ?? '',
            'meta_keywords' => $seoRoute?->meta_keywords ?? '',
            'og_title' => $seoRoute?->og_title ?? '',
            'og_description' => $seoRoute?->og_description ?? '',
            'og_image' => $seoRoute?->og_image ?? '',
            'twitter_title' => $seoRoute?->twitter_title ?? '',
            'twitter_description' => $seoRoute?->twitter_description ?? '',
            'twitter_image' => $seoRoute?->twitter_image ?? '',
            'canonical_url' => $seoRoute?->canonical_url ?? '',
            'robots' => $seoRoute?->robots ?? '',
            'no_index' => $seoRoute?->no_index ?? false,
            'priority' => $seoRoute?->priority ?? 0.5,
            'changefreq' => $seoRoute?->changefreq ?? 'monthly',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate([
            'form.meta_title' => 'nullable|string|max:70',
            'form.meta_description' => 'nullable|string|max:300',
            'form.meta_keywords' => 'nullable|string|max:255',
            'form.og_title' => 'nullable|string|max:70',
            'form.og_description' => 'nullable|string|max:300',
            'form.og_image' => 'nullable|string|max:255',
            'form.twitter_title' => 'nullable|string|max:70',
            'form.twitter_description' => 'nullable|string|max:300',
            'form.twitter_image' => 'nullable|string|max:255',
            'form.canonical_url' => 'nullable|string|max:255',
            'form.robots' => 'nullable|string|max:50',
            'form.no_index' => 'boolean',
            'form.priority' => 'numeric|between:0,1',
            'form.changefreq' => 'in:always,hourly,daily,weekly,monthly,yearly,never',
        ]);

        SeoStaticRoute::updateOrCreate(
            ['route_name' => $this->form['route_name']],
            [
                'meta_title' => $this->form['meta_title'] ?: null,
                'meta_description' => $this->form['meta_description'] ?: null,
                'meta_keywords' => $this->form['meta_keywords'] ?: null,
                'og_title' => $this->form['og_title'] ?: null,
                'og_description' => $this->form['og_description'] ?: null,
                'og_image' => $this->form['og_image'] ?: null,
                'twitter_title' => $this->form['twitter_title'] ?: null,
                'twitter_description' => $this->form['twitter_description'] ?: null,
                'twitter_image' => $this->form['twitter_image'] ?: null,
                'canonical_url' => $this->form['canonical_url'] ?: null,
                'robots' => $this->form['robots'] ?: null,
                'no_index' => $this->form['no_index'] ?? false,
                'priority' => $this->form['priority'] ?? 0.5,
                'changefreq' => $this->form['changefreq'] ?? 'monthly',
            ]
        );

        Cache::forget('seo.sitemap');
        Cache::forget('seo.robots');

        $this->editingId = null;
        $this->form = [];
        $this->loadRoutes();

        $this->dispatch('notify', ['message' => 'SEO settings saved successfully', 'type' => 'success']);
    }

    public function cancel(): void
    {
        $this->editingId = null;
        $this->form = [];
    }

    private function getRouteLabel(string $routeName): string
    {
        return match ($routeName) {
            'home' => 'Homepage',
            'services' => 'Services List',
            'gallery' => 'Gallery',
            'quote' => 'Get a Quote',
            'contact' => 'Contact Us',
            default => ucfirst(str_replace(['.', '_'], ' ', $routeName)),
        };
    }
}; ?>

<flux:main>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-6">
            <flux:heading size="xl" level="1">{{ __('Static Routes SEO') }}</flux:heading>
        </div>

        <div class="space-y-4">
            @foreach ($routes as $index => $route)
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    @if ($editingId === $index)
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <flux:heading size="lg">{{ $route['route_label'] }}</flux:heading>
                                <flux:badge size="sm" variant="primary">{{ $route['route_name'] }}</flux:badge>
                            </div>

                            <form wire:submit="save" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <flux:input wire:model="form.meta_title" label="Meta Title" placeholder="Page title for search results" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <flux:textarea wire:model="form.meta_description" label="Meta Description" placeholder="Brief description for search results" rows="3" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <flux:input wire:model="form.meta_keywords" label="Meta Keywords" placeholder="keyword1, keyword2, keyword3" />
                                    </div>

                                    <flux:input wire:model="form.og_title" label="OpenGraph Title" placeholder="Title for social sharing" />
                                    <flux:input wire:model="form.twitter_title" label="Twitter Title" placeholder="Title for Twitter cards" />

                                    <div class="md:col-span-2">
                                        <flux:textarea wire:model="form.og_description" label="OpenGraph Description" placeholder="Description for social sharing" rows="3" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <flux:textarea wire:model="form.twitter_description" label="Twitter Description" placeholder="Description for Twitter cards" rows="3" />
                                    </div>

                                    <flux:input wire:model="form.og_image" label="OpenGraph Image URL" placeholder="https://example.com/image.jpg" />
                                    <flux:input wire:model="form.twitter_image" label="Twitter Image URL" placeholder="https://example.com/image.jpg" />

                                    <flux:input wire:model="form.canonical_url" label="Canonical URL" placeholder="https://example.com/canonical-page" />
                                    <flux:input wire:model="form.robots" label="Robots Directive" placeholder="index, follow" />

                                    <flux:input type="number" wire:model="form.priority" label="Sitemap Priority" step="0.1" min="0" max="1" />

                                    <div>
                                        <flux:label>Change Frequency</flux:label>
                                        <select wire:model="form.changefreq" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="always">Always</option>
                                            <option value="hourly">Hourly</option>
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="yearly">Yearly</option>
                                            <option value="never">Never</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center">
                                        <flux:checkbox wire:model="form.no_index" label="No Index (hide from search engines)" />
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                                    <flux:button type="button" wire:click="cancel" variant="ghost">Cancel</flux:button>
                                    <flux:button type="submit" variant="primary">Save SEO Settings</flux:button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="p-4 flex items-center justify-between hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <flux:heading size="md">{{ $route['route_label'] }}</flux:heading>
                                        <flux:badge size="sm" variant="outline">{{ $route['route_name'] }}</flux:badge>
                                        @if ($route['exists'])
                                            <flux:badge size="sm" variant="primary">Configured</flux:badge>
                                        @else
                                            <flux:badge size="sm" color="amber">Default</flux:badge>
                                        @endif
                                    </div>
                                    @if ($route['meta_title'])
                                        <p class="text-sm text-zinc-500 mt-1">{{ $route['meta_title'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <flux:button wire:click="edit({{ $index }})" variant="ghost" size="sm" icon="pencil">
                                Edit
                            </flux:button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</flux:main>
