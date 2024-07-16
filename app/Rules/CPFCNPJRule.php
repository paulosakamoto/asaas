<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 *
 */
class CPFCNPJRule implements ValidationRule
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

        if (strlen($value) === 11) {
            (new CPFRule)->validate($attribute, $value, $fail);
            return;
        }

        if (strlen($value) === 14) {
            (new CNPJRule)->validate($attribute, $value, $fail);
            return;
        }

        $fail('The :attribute must be 11 numbers or 14 numbers.');
    }
}
