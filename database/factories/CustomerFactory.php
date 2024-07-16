<?php

namespace Database\Factories;

use App\Enums\PersonType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'cpf_cnpj' => fake()->unique()->cpf(false),
            'mobile_phone' => fake()->cellphone(false, true),
            'person_type' => PersonType::FISICA->value,
            'asaas_id' => fake()->uuid(),
        ];
    }

    /**
     * @return CustomerFactory|Factory
     */
    public function fisica(): Factory
    {
        return $this->state(function (): array {
            return [
                'cpf_cnpj' => fake()->unique()->cpf(false),
                'person_type' => PersonType::FISICA->value,
            ];
        });
    }

    /**
     * @return CustomerFactory|Factory
     */
    public function juridica(): Factory
    {
        return $this->state(function (): array {
            return [
                'cpf_cnpj' => fake()->unique()->cnpj(false),
                'person_type' => PersonType::JURIDICA->value,
            ];
        });
    }
}
