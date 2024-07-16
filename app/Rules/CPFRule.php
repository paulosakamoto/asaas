<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 *
 */
class CPFRule implements ValidationRule
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

        if (strlen($value) !== 11) {
            $fail('The :attribute must be 11 numbers.');
            return;
        }

        if (!$this->validateCpfNumber($value)) {
            $fail('The :attribute must be a valid CPF number.');
        }
    }

    /**
     * @param string $number
     * @return bool
     */
    public function validateCpfNumber(string $number)
    {
        if (preg_match('/(\d)\1{10}/', $number)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $number[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($number[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
