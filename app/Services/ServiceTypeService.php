<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ServiceType;
use Illuminate\Support\Str;

final class ServiceTypeService
{
    public function create(array $data): ServiceType
    {
        $data['slug'] = Str::slug($data['name']);

        return ServiceType::create($data);
    }

    public function update(ServiceType $serviceType, array $data): ServiceType
    {
        $data['slug'] = Str::slug($data['name']);

        $serviceType->update($data);

        return $serviceType->fresh();
    }

    public function delete(ServiceType $serviceType): void
    {
        $serviceType->delete();
    }
}
