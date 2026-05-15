<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Testimonial;

final class TestimonialService
{
    public function create(array $data): Testimonial
    {
        return Testimonial::create($data);
    }

    public function update(Testimonial $testimonial, array $data): Testimonial
    {
        $testimonial->update($data);

        return $testimonial->fresh();
    }

    public function delete(Testimonial $testimonial): void
    {
        $testimonial->delete();
    }
}
