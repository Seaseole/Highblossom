<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Support\HeroAssets;
use App\Trait\CompanyValidationRules;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Validate and authorize company settings updates.
 */
final class CompanySettingRequest extends FormRequest
{
    use CompanyValidationRules;

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
        return $this->validateCompanyRules();
    }

    /**
     * Reject a foreground cutout that is not the same shape as the photograph behind it.
     *
     * The cinematic hero stacks the two layers in one box and aligns them by construction, so
     * the only thing that can put the subject off its own background is a cutout exported from
     * a different frame. That is invisible in the admin grid and glaring on the homepage, so it
     * is refused at the field that caused it rather than dropped silently at save.
     *
     * @return Closure(Validator): void
     */
    public function after(): Closure
    {
        return function (Validator $validator): void {
            foreach ((array) $this->input('home_hero_slides', []) as $index => $slide) {
                $background = trim((string) (is_array($slide) ? $slide['background'] ?? '' : ''));
                $cutout = trim((string) (is_array($slide) ? $slide['cutout'] ?? '' : ''));

                if ($background === '' || $cutout === '') {
                    continue;
                }

                $ratio = HeroAssets::aspectRatio($background);
                $foreground = HeroAssets::aspectRatio($cutout);

                if ($ratio === null || $foreground === null || $ratio <= 0) {
                    // Unreadable geometry already failed the path rule above; saying it twice
                    // would only bury the message that actually explains the problem.
                    continue;
                }

                if (abs($ratio - $foreground) / $ratio > HeroAssets::ASPECT_TOLERANCE) {
                    $validator->errors()->add(
                        "home_hero_slides.{$index}.cutout",
                        'This cutout is a different shape from the photo behind it. Export it from the same image at the same pixel size, or clear it to use the photo alone.'
                    );
                }
            }
        };
    }
}
