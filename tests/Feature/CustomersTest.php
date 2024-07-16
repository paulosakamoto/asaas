<?php

namespace Tests\Feature;

use App\Enums\PersonType;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Http;
use JsonException;
use Tests\TestCase;

/**
 *
 */
class CustomersTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @return void
     */
    public function test_customers_index_page_is_displayed(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.index'));

        $response->assertOk();
    }

    /**
     * @return void
     */
    public function test_customers_create_page_is_displayed(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.create'));

        $response->assertOk();
    }

    /**
     * @return void
     */
    public function test_customers_edit_page_is_displayed(): void
    {
        $customer = Customer::factory()->fisica()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.edit', ['customer' => $customer]));

        $response->assertOk();
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function test_that_customer_form_is_validated(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('customers.store'), [
            'name' => '',
            'email' => '',
            'person_type' => '',
            'cpf' => '',
            'cnpj' => '',
            'mobile_phone' => '',
        ]);

        $customer = Customer::first();
        $this->assertNull($customer);
        $response->assertSessionHasErrorsIn('customer', [
            'name', 'email', 'person_type',
        ]);

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'person_type' => PersonType::FISICA->value,
            'cpf' => '',
            'cnpj' => '',
            'mobile_phone' => '11912345678',
        ]);

        $customer = Customer::first();
        $this->assertNull($customer);
        $response->assertSessionHasErrorsIn('customer', [
            'cpf',
        ]);

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'person_type' => PersonType::JURIDICA->value,
            'cpf' => '',
            'cnpj' => '',
            'mobile_phone' => '11912345678',
        ]);

        $customer = Customer::first();
        $this->assertNull($customer);
        $response->assertSessionHasErrorsIn('customer', [
            'cnpj',
        ]);

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'person_type' => PersonType::FISICA->value,
            'cpf' => '60355652048',
            'cnpj' => '',
            'mobile_phone' => '(11) 91234-5678',
        ]);
        $customer = Customer::first();
        $this->assertNull($customer);
        $response->assertSessionHasErrorsIn('customer', [
            'mobile_phone',
        ]);

        $response = $this->post(route('customers.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'person_type' => PersonType::FISICA->value,
            'cpf' => '60355652048',
            'cnpj' => '',
            'mobile_phone' => '479988776',
        ]);
        $customer = Customer::first();
        $this->assertNull($customer);
        $response->assertSessionHasErrorsIn('customer', [
            'mobile_phone',
        ]);
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function test_customers_can_be_created(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers' => Http::response([
                'id' => 'cus_123456',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'personType' => PersonType::FISICA->value,
                'cpfCnpj' => '60355652048',
                'mobilePhone' => '11912345678',
                'object' => 'customer',
            ])
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.store'), [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'person_type' => PersonType::FISICA->value,
                'cpf' => '60355652048',
                'mobile_phone' => '11912345678',
            ]);

        $customer = Customer::first();

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('customers.edit', ['customer' => $customer]));

        $this->assertSame('cus_123456', $customer->getAttribute('asaas_id'));
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function test_customer_can_be_updated(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers/cus_123456' => Http::response([
                'id' => 'cus_123456',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'personType' => PersonType::FISICA->value,
                'cpfCnpj' => '60355652048',
                'mobilePhone' => '11912345678',
                'object' => 'customer',
            ])
        ]);

        $customer = Customer::factory()->fisica()->create(['asaas_id' => 'cus_123456']);
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put(route('customers.update', ['customer' => $customer]), [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'person_type' => $customer->person_type->value,
                'cpf' => '60355652048',
                'mobile_phone' => '11912345678',
            ]);

        $customer->refresh();

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('customers.edit', ['customer' => $customer]));

        $this->assertSame('John Doe', $customer->name);
        $this->assertSame('john@example.com', $customer->email);
        $this->assertSame('60355652048', $customer->cpf_cnpj);
        $this->assertSame('11912345678', $customer->mobile_phone);
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function test_customer_can_be_deleted(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers/cus_123456' => Http::response([
                'id' => 'cus_123456',
                'deleted' => true
            ])
        ]);

        $customer = Customer::factory()->fisica()->create(['asaas_id' => 'cus_123456']);
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete(route('customers.delete', ['customer' => $customer]));

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('customers.index'));

        $this->assertDatabaseEmpty('customers');
    }
}
