<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CNPJRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/[^\d]+/', $value)) {
            $fail('The :attribute must have only numbers.');
            return;
        }

        if (strlen($value) !== 14) {
            $fail('The :attribute must be 14 numbers.');
            return;
        }

        if (!$this->validateCnpjNumber($value)) {
            $fail('The :attribute must be a valid CNPJ number.');
        }
    }

    /**
     * @param string $number
     * @return bool
     */
    public function validateCnpjNumber(string $number)
    {
        if (preg_match('/(\d)\1{13}/', $number)) {
            return false;
        }

        for ($i = 0, $j = 5, $sum = 0; $i < 12; $i++)
        {
            $sum += $number[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $mod = $sum % 11;

        if ($number[12] != ($mod < 2 ? 0 : 11 - $mod))
            return false;

        for ($i = 0, $j = 6, $sum = 0; $i < 13; $i++)
        {
            $sum += $number[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $mod = $sum % 11;

        return $number[13] == ($mod < 2 ? 0 : 11 - $mod);
    }
}
