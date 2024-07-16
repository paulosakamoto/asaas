<?php

namespace App\Http\Requests;

use App\Enums\BillingType;
use App\Rules\CPFCNPJRule;
use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 *
 */
class PaymentRequest extends FormRequest
{
    /**
     * @var string
     */
    protected $errorBag = 'payment';

    /**
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        $requiredIfCreditCard = 'requiredIf:billing_type,' . BillingType::CREDIT_CARD->value;
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'billing_type' => ['required', Rule::enum(BillingType::class)],
            'value' => ['required', 'decimal:2'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],

            'credit_card' => [$requiredIfCreditCard, 'array'],
            'credit_card.holder_name' => [$requiredIfCreditCard, 'string', 'max:255'],
            'credit_card.number' => [$requiredIfCreditCard, 'string', 'min:14', 'max:20'],
            'credit_card.expiry_month' => [$requiredIfCreditCard, 'string', 'size:2'],
            'credit_card.expiry_year' => [$requiredIfCreditCard, 'string', 'size:4'],
            'credit_card.ccv' => [$requiredIfCreditCard, 'string', 'max:4'],

            'holder' => [$requiredIfCreditCard, 'array'],
            'holder.name' => [$requiredIfCreditCard, 'string', 'max:255'],
            'holder.email' => [$requiredIfCreditCard, 'string', 'lowercase', 'email', 'max:255'],
            'holder.cpf_cnpj' => [$requiredIfCreditCard, 'string', new CPFCNPJRule],
            'holder.postal_code' => [$requiredIfCreditCard, 'string', 'size:8'],
            'holder.address_number' => [$requiredIfCreditCard, 'string', 'max:255'],
            'holder.address_complement' => ['sometimes', 'nullable', 'string', 'max:255'],
            'holder.phone' => ['sometimes', 'nullable', new PhoneRule],
            'holder.mobile_phone' => [$requiredIfCreditCard, new PhoneRule],
        ];
    }
}
