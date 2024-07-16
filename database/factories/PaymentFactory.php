<?php

namespace Database\Factories;

use App\Enums\BillingType;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value' => fake()->randomFloat(2, 10, 9999),
            'billing_type' => BillingType::BOLETO->value,
            'status' => fake()->randomElement(PaymentStatus::values()),
            'due_date' => fake()->dateTimeBetween('+1 day', '+90 days'),
            'asaas_id' => fake()->uuid(),
            'customer_id' => fake()->unique()->randomDigit(),
        ];
    }

    /**
     * @return PaymentFactory|Factory
     */
    public function pix(): Factory
    {
        return $this->state(function (): array {
            return [
                'billing_type' => BillingType::PIX->value,
            ];
        });
    }

    /**
     * @return PaymentFactory|Factory
     */
    public function boleto(): Factory
    {
        return $this->state(function (): array {
            return [
                'billing_type' => BillingType::BOLETO->value,
            ];
        });
    }

    /**
     * @return PaymentFactory|Factory
     */
    public function creditCard()
    {
        return $this->state(function (): array {
            return [
                'billing_type' => BillingType::CREDIT_CARD->value,
            ];
        });
    }
}
