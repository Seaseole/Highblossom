<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;

final readonly class ContactNumberService
{
    /**
     * Build contact numbers collection from company settings.
     *
     * Blank settings produce no card at all: an unset WhatsApp number used to
     * render a headline card with an empty number and a dead `wa.me/` link.
     *
     * @param string $whatsappDefault    Default WhatsApp number
     * @param array  $whatsappAdditional Additional WhatsApp numbers
     * @param string $primaryPhone       Primary phone number
     * @param string $secondaryPhone     Secondary phone number, empty when unused
     *
     * @return Collection Collection of contact number objects
     */
    public function buildContactNumbers(string $whatsappDefault, array $whatsappAdditional, string $primaryPhone, string $secondaryPhone): Collection
    {
        $contactNumbers = collect();

        // Add primary WhatsApp
        if ($this->isUsable($whatsappDefault)) {
            $contactNumbers->push($this->createNumberObject('WhatsApp', $whatsappDefault, true, true));
        }

        // Add additional WhatsApp numbers
        $this->addAdditionalWhatsAppNumbers($contactNumbers, $whatsappAdditional, $whatsappDefault);

        // Add primary phone if different
        if ($this->isUsable($primaryPhone) && $primaryPhone !== $whatsappDefault) {
            $contactNumbers->push($this->createNumberObject('Phone', $primaryPhone, false, false));
        }

        // Add the secondary line when set and not a duplicate of the numbers above
        if ($this->isUsable($secondaryPhone) && $secondaryPhone !== $whatsappDefault && $secondaryPhone !== $primaryPhone) {
            $contactNumbers->push($this->createNumberObject('Phone 2', $secondaryPhone, false, false));
        }

        return $contactNumbers;
    }

    /**
     * Determine whether a configured number is worth rendering a card for.
     */
    private function isUsable(string $number): bool
    {
        return trim($number) !== '';
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
            $label = is_array($item) ? ($item['label'] ?? 'WhatsApp '.($index + 2)) : 'WhatsApp '.($index + 2);

            if ($number && $number !== $default) {
                $collection->push($this->createNumberObject($label, $number, true, false));
            }
        }
    }
}
