<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\AdminNavLayout;
use App\Enums\Theme;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validate and authorize theme appearance updates.
 */
final class AppearanceRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'theme' => ['required', Rule::enum(Theme::class)],
            // Required rather than nullable on purpose: this form always sends a choice, and a
            // missing key here would mean the tab silently un-set someone's layout. Choosing to
            // follow the site-wide default again is done by the account having never saved one.
            'admin_nav_layout' => ['required', Rule::enum(AdminNavLayout::class)],
        ];
    }
}
