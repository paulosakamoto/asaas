<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 *
 */
class PhoneRule implements ValidationRule
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

        if (!in_array(strlen($value), [10, 11])) {
            $fail('The :attribute must be 10 or 11 numbers.');
        }
    }
}
