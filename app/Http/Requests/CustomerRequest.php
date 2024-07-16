<?php

namespace App\Http\Requests;

use App\Enums\PersonType;
use App\Rules\CNPJRule;
use App\Rules\CPFRule;
use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 *
 */
class CustomerRequest extends FormRequest
{
    /**
     * @var string
     */
    protected $errorBag = 'customer';

    /**
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'person_type' => ['required', Rule::enum(PersonType::class)],
            'cpf' => ['nullable', 'sometimes', 'required_if:person_type,' . PersonType::FISICA->value, new CPFRule],
            'cnpj' => ['nullable', 'sometimes', 'required_if:person_type,' . PersonType::JURIDICA->value, new CNPJRule],
            'mobile_phone' => ['nullable', new PhoneRule],
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $personType = $this->input('person_type');
        if ($personType === PersonType::FISICA->value) {
            $this->replace(['cpf_cnpj' => $this->cpf]);
        } elseif ($personType === PersonType::JURIDICA->value) {
            $this->replace(['cpf_cnpj' => $this->cnpj]);
        }
    }
}
