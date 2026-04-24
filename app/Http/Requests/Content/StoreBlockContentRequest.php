<?php

declare(strict_types=1);

namespace App\Http\Requests\Content;

use Highblossom\ContentBlocks\Services\BlockRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'featured_image_path' => 'nullable|string',
            'featured_image_url' => 'nullable|url|max:500',
            'delete_featured_image' => 'nullable|boolean',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $this->validateContentBlocks($validator);
        });
    }

    /**
     * Validate that content blocks have valid types and attributes.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    private function validateContentBlocks(Validator $validator): void
    {
        $content = $this->input('content');

        if (empty($content)) {
            return;
        }

        $blocks = json_decode($content, true);

        if (!is_array($blocks)) {
            $validator->errors()->add('content', 'Content must be a valid JSON array.');

            return;
        }

        $registry = app(BlockRegistry::class);

        foreach ($blocks as $index => $block) {
            $blockKey = "content.{$index}";

            // Check block structure
            if (!is_array($block)) {
                $validator->errors()->add($blockKey, "Block at position {$index} must be an array.");
                continue;
            }

            // Check type exists
            if (!isset($block['type'])) {
                $validator->errors()->add("{$blockKey}.type", "Block at position {$index} is missing required 'type' field.");
                continue;
            }

            $type = $block['type'];

            if (!is_string($type) || empty($type)) {
                $validator->errors()->add("{$blockKey}.type", "Block at position {$index} has invalid 'type' value.");
                continue;
            }

            // Check type is registered
            if (!$registry->has($type)) {
                $validTypes = $registry->types();
                $validator->errors()->add(
                    "{$blockKey}.type",
                    "Block type '{$type}' at position {$index} is not registered. Valid types: " . implode(', ', $validTypes)
                );
                continue;
            }

            // Check attributes is array
            $attributes = $block['attributes'] ?? [];
            if (!is_array($attributes)) {
                $validator->errors()->add("{$blockKey}.attributes", "Block at position {$index} must have 'attributes' as an array.");
                continue;
            }

            // Validate block-specific rules
            $blockService = $registry->get($type);
            $blockRules = $blockService->getValidationRules();

            if (!empty($blockRules)) {
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

        // Generate slug from title
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);

        // Set published_at if publishing
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        return $validated;
    }
}
