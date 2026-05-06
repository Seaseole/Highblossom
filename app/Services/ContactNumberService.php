<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;

final readonly class ContactNumberService
{
    /**
     * Build contact numbers collection from company settings.
     *
     * @param string $whatsappDefault Default WhatsApp number
     * @param array $whatsappAdditional Additional WhatsApp numbers
     * @param string $primaryPhone Primary phone number
     * @return Collection Collection of contact number objects
     */
    public function buildContactNumbers(string $whatsappDefault, array $whatsappAdditional, string $primaryPhone): Collection
    {
        $contactNumbers = collect();

        // Add primary WhatsApp
        $contactNumbers->push($this->createNumberObject('WhatsApp', $whatsappDefault, true, true));

        // Add additional WhatsApp numbers
        $this->addAdditionalWhatsAppNumbers($contactNumbers, $whatsappAdditional, $whatsappDefault);

        // Add primary phone if different
        if ($primaryPhone !== $whatsappDefault) {
            $contactNumbers->push($this->createNumberObject('Phone', $primaryPhone, false, false));
        }

        return $contactNumbers;
    }

    /**
     * Create a standardized contact number object.
     */
    private function createNumberObject(string $label, string $number, bool $isWhatsapp, bool $isPrimary): object
    {
        return (object) [
            'label' => $label,
            'phone_number' => $number,
            'formatted_number' => $number,
            'is_whatsapp' => $isWhatsapp,
            'is_primary' => $isPrimary,
        ];
    }

    /**
     * Process and add additional WhatsApp numbers to the collection.
     */
    private function addAdditionalWhatsAppNumbers(Collection $collection, array $additional, string $default): void
    {
        if (empty($additional)) {
            return;
        }

        foreach ($additional as $index => $item) {
            $number = is_array($item) ? ($item['number'] ?? $item[0] ?? '') : $item;
            $label = is_array($item) ? ($item['label'] ?? 'WhatsApp ' . ($index + 2)) : 'WhatsApp ' . ($index + 2);

            if ($number && $number !== $default) {
                $collection->push($this->createNumberObject($label, $number, true, false));
            }
        }
    }
}
