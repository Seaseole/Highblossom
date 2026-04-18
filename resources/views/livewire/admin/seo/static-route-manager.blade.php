<flux:main class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Static Route SEO Management</h1>
            <p class="mt-2 text-gray-600">Manage SEO metadata for non-content pages like home, services list, gallery, etc.</p>
        </div>

        @if($editingId !== null)
            <flux:card class="mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold">Edit SEO: {{ $form['route_name'] ?? '' }}</h2>
                    <flux:button wire:click="cancel" variant="ghost">Cancel</flux:button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h3 class="font-medium text-gray-900 border-b pb-2">Basic Meta Tags</h3>

                        <flux:input
                            wire:model="form.meta_title"
                            label="Meta Title"
                            placeholder="Enter meta title..."
                            maxlength="70"
                        />
                        <p class="text-sm text-gray-500">{{ strlen($form['meta_title'] ?? '') }}/70 characters</p>

                        <flux:textarea
                            wire:model="form.meta_description"
                            label="Meta Description"
                            placeholder="Enter meta description..."
                            rows="3"
                            maxlength="300"
                        />
                        <p class="text-sm text-gray-500">{{ strlen($form['meta_description'] ?? '') }}/300 characters</p>

                        <flux:input
                            wire:model="form.meta_keywords"
                            label="Meta Keywords"
                            placeholder="keyword1, keyword2, keyword3..."
                        />
                    </div>

                    <div class="space-y-4">
                        <h3 class="font-medium text-gray-900 border-b pb-2">OpenGraph / Social</h3>

                        <flux:input
                            wire:model="form.og_title"
                            label="OG Title"
                            placeholder="Facebook/LinkedIn title..."
                            maxlength="70"
                        />

                        <flux:textarea
                            wire:model="form.og_description"
                            label="OG Description"
                            placeholder="Social media description..."
                            rows="3"
                            maxlength="300"
                        />

                        <flux:input
                            wire:model="form.og_image"
                            label="OG Image URL"
                            placeholder="https://example.com/image.jpg"
                        />
                    </div>

                    <div class="space-y-4">
                        <h3 class="font-medium text-gray-900 border-b pb-2">Twitter Cards</h3>

                        <flux:input
                            wire:model="form.twitter_title"
                            label="Twitter Title"
                            placeholder="Twitter card title..."
                            maxlength="70"
                        />

                        <flux:textarea
                            wire:model="form.twitter_description"
                            label="Twitter Description"
                            placeholder="Twitter card description..."
                            rows="3"
                            maxlength="300"
                        />

                        <flux:input
                            wire:model="form.twitter_image"
                            label="Twitter Image URL"
                            placeholder="https://example.com/twitter-image.jpg"
                        />
                    </div>

                    <div class="space-y-4">
                        <h3 class="font-medium text-gray-900 border-b pb-2">Advanced Settings</h3>

                        <flux:input
                            wire:model="form.canonical_url"
                            label="Canonical URL"
                            placeholder="https://example.com/canonical-url"
                        />

                        <flux:input
                            wire:model="form.robots"
                            label="Robots"
                            placeholder="noindex, nofollow"
                        />

                        <div class="grid grid-cols-2 gap-4">
                            <flux:select wire:model="form.changefreq" label="Change Frequency">
                                <option value="always">Always</option>
                                <option value="hourly">Hourly</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly" selected>Monthly</option>
                                <option value="yearly">Yearly</option>
                                <option value="never">Never</option>
                            </flux:select>

                            <flux:input
                                wire:model="form.priority"
                                type="number"
                                step="0.1"
                                min="0"
                                max="1"
                                label="Priority"
                            />
                        </div>

                        <flux:checkbox wire:model="form.no_index" label="No Index (prevent search indexing)" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <flux:button wire:click="cancel" variant="secondary">Cancel</flux:button>
                    <flux:button wire:click="save" variant="primary">Save SEO Settings</flux:button>
                </div>
            </flux:card>
        @endif

        <flux:card>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meta Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($routes as $index => $route)
                        <tr>
                            <td class="px-4 py-4">
                                <div class="font-medium text-gray-900">{{ $route['route_label'] }}</div>
                                <div class="text-sm text-gray-500">{{ $route['route_name'] }}</div>
                            </td>
                            <td class="px-4 py-4">
                                @if($route['meta_title'])
                                    <div class="text-sm text-gray-900 truncate max-w-xs">{{ $route['meta_title'] }}</div>
                                @else
                                    <span class="text-sm text-gray-400 italic">Not set</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($route['no_index'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        No Index
                                    </span>
                                @elseif($route['exists'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Configured
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Default
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-900">{{ $route['priority'] }}</span>
                                <span class="text-sm text-gray-500">({{ $route['changefreq'] }})</span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <flux:button wire:click="edit({{ $index }})" size="sm" variant="primary">
                                    Edit
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                No static routes configured. Check config/seo.php to define routes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </flux:card>
    </div>
</flux:main>
