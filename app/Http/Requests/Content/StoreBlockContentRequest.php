<?php

declare(strict_types=1);

namespace App\Http\Requests\Content;

use App\Models\Post;
use App\Rules\SafeStoredUploadPath;
use Highblossom\ContentBlocks\Services\BlockRegistry;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Validate and authorize content block storage requests.
 */
final class StoreBlockContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:'.Post::TITLE_MAX],
            'excerpt' => ['nullable', 'string', 'max:'.Post::EXCERPT_MAX],
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'featured_image_path' => ['nullable', 'string', new SafeStoredUploadPath(['uploads/blog', 'uploads/images', 'uploads', 'gallery'])],
            'featured_image_url' => 'nullable|url|max:500',
            'delete_featured_image' => 'nullable|boolean',
            'status' => ['required', Rule::in(Post::STATUSES)],
            'published_at' => ['nullable', 'date'],
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    /**
     * Custom messages so the publish gate reads as guidance rather than a rule id.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.max' => 'The title must not exceed '.Post::TITLE_MAX.' characters.',
            'excerpt.max' => 'The excerpt must not exceed '.Post::EXCERPT_MAX.' characters.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $this->validateContentBlocks($validator);
            $this->validatePublishReadiness($validator);
        });
    }

    /**
     * Refuse to publish a post that would read as unfinished, while leaving drafts open.
     */
    private function validatePublishReadiness(Validator $validator): void
    {
        if ($this->input('status') !== 'published') {
            return;
        }

        $blocks = json_decode((string) $this->input('content'), true);

        if (! is_array($blocks) || $blocks === []) {
            $validator->errors()->add('content', 'Add at least one content block before publishing.');
        }

        if (trim((string) $this->input('excerpt')) === '') {
            $validator->errors()->add('excerpt', 'An excerpt is required before publishing.');
        }

        if (array_filter((array) $this->input('categories', [])) === []) {
            $validator->errors()->add('categories', 'Choose at least one category before publishing.');
        }
    }

    /**
     * Validate that content blocks have valid types and attributes.
     */
    private function validateContentBlocks(Validator $validator): void
    {
        $content = $this->input('content');

        if (empty($content)) {
            return;
        }

        $blocks = json_decode($content, true);

        if (! is_array($blocks)) {
            $validator->errors()->add('content', 'Content must be a valid JSON array.');

            return;
        }

        $registry = app(BlockRegistry::class);

        foreach ($blocks as $index => $block) {
            $blockKey = "content.{$index}";

            // Check block structure
            if (! is_array($block)) {
                $validator->errors()->add($blockKey, "Block at position {$index} must be an array.");

                continue;
            }

            // Check type exists
            if (! isset($block['type'])) {
                $validator->errors()->add("{$blockKey}.type", "Block at position {$index} is missing required 'type' field.");

                continue;
            }

            $type = $block['type'];

            if (! is_string($type) || empty($type)) {
                $validator->errors()->add("{$blockKey}.type", "Block at position {$index} has invalid 'type' value.");

                continue;
            }

            // Check type is registered
            if (! $registry->has($type)) {
                $validTypes = $registry->types();
                $validator->errors()->add(
                    "{$blockKey}.type",
                    "Block type '{$type}' at position {$index} is not registered. Valid types: ".implode(', ', $validTypes)
                );

                continue;
            }

            // Check attributes is array
            $attributes = $block['attributes'] ?? [];
            if (! is_array($attributes)) {
                $validator->errors()->add("{$blockKey}.attributes", "Block at position {$index} must have 'attributes' as an array.");

                continue;
            }

            // Validate block-specific rules
            $blockService = $registry->get($type);
            $blockRules = $blockService->getValidationRules();

            if (! empty($blockRules)) {
                $blockValidator = \Illuminate\Support\Facades\Validator::make($attributes, $blockRules);

                if ($blockValidator->fails()) {
                    foreach ($blockValidator->errors()->all() as $error) {
                        $validator->errors()->add("{$blockKey}.attributes", "Block {$type} at position {$index}: {$error}");
                    }
                }
            }
        }
    }

    /**
     * Get validated content as array.
     *
     * @return array<string, mixed>
     */
    public function validatedContent(): array
    {
        $validated = $this->validated();

        // Convert content JSON string to array
        $validated['content'] = isset($validated['content']) && $validated['content'] !== ''
            ? json_decode($validated['content'], true) ?? []
            : [];

        // Generate a slug that will not collide with an existing post.
        $validated['slug'] = $this->uniqueSlugFor($validated['title']);

        // The post form always offers both chip groups, so an absent key means the
        // author cleared them rather than that the field was never present.
        $validated['categories'] ??= [];
        $validated['tags'] ??= [];

        // Set published_at if publishing
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        return $validated;
    }

    /**
     * Build a slug for the title, disambiguating against every other post.
     */
    private function uniqueSlugFor(string $title): string
    {
        $base = Str::slug($title) ?: 'post';
        $current = $this->route('post');
        $slug = $base;
        $suffix = 2;

        while (Post::where('slug', $slug)
            ->when($current instanceof Post, fn ($query) => $query->whereKeyNot($current->getKey()))
            ->exists()
        ) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
