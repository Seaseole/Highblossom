<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class SeoStaticRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $routeId = $this->route('id');

        return [
            'route_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('seo_static_routes', 'route_name')->ignore($routeId),
            ],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:70'],
            'og_description' => ['nullable', 'string', 'max:300'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'twitter_title' => ['nullable', 'string', 'max:70'],
            'twitter_description' => ['nullable', 'string', 'max:300'],
            'twitter_image' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'string', 'max:255'],
            'robots' => ['nullable', 'string', 'max:50'],
            'no_index' => ['boolean'],
            'priority' => ['numeric', 'between:0,1'],
            'changefreq' => ['in:always,hourly,daily,weekly,monthly,yearly,never'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'no_index' => $this->boolean('no_index'),
            'priority' => $this->input('priority', 0.5),
            'changefreq' => $this->input('changefreq', 'monthly'),
        ]);
    }
}
