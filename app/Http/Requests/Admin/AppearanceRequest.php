<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\Theme;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class AppearanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'theme' => ['required', Rule::enum(Theme::class)],
        ];
    }
}
