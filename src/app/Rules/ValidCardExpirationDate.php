<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class ValidCardExpirationDate implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {
            // Ensure the format is MM/YYYY
            [$month, $year] = explode('/', $value);
            if (!checkdate((int)$month, 1, (int)$year)) {
                return false;
            }

            // Check if the date is in the future
            $expirationDate = Carbon::createFromDate((int)$year, (int)$month, 1);
            return $expirationDate->endOfMonth()->isFuture();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The expiration date must be in the future and in MM/YYYY format.';
    }
}
