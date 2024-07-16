<?php

namespace Tests\Feature;

use App\Enums\BillingType;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use JsonException;
use Tests\TestCase;

/**
 *
 */
class PaymentsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @return void
     */
    public function test_payments_index_page_is_displayed(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('payments.index'));

        $response->assertOk();
    }

    /**
     * @return void
     */
    public function test_payments_create_page_is_displayed(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('payments.create'));

        $response->assertOk();
    }

    /**
     * @return void
     */
    public function test_payments_edit_page_is_displayed(): void
    {
        $customer = Customer::factory()->create();
        $payment = Payment::factory()->boleto()->create(['customer_id' => $customer->id]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('payments.edit', ['payment' => $payment]));

        $response->assertOk();
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function test_that_payment_form_is_validated(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('payments.store'), [
            'customer_id' => '',
            'billing_type' => '',
            'value' => '',
            'due_date' => '',
        ]);

        $payment = Payment::first();
        $this->assertNull($payment);
        $response->assertSessionHasErrorsIn('payment', [
            'customer_id', 'billing_type', 'value', 'due_date',
        ]);

        $response = $this->post(route('payments.store'), [
            'customer_id' => '123',
            'billing_type' => BillingType::BOLETO->value,
            'value' => 99.99,
            'due_date' => '2024-01-01',
        ]);

        $payment = Payment::first();
        $this->assertNull($payment);
        $response->assertSessionHasErrorsIn('payment', [
            'customer_id', 'due_date',
        ]);
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function test_payments_can_be_created(): void
    {
        $this->actingAs(User::factory()->create());

        $dueDate = Carbon::now()->addDay()->format('Y-m-d');
        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments' => Http::response([
                'id' => 'pay_123456',
                'customer' => 'cus_123456',
                'billingType' => BillingType::BOLETO->value,
                'status' => PaymentStatus::PENDING->value,
                'value' => 99.99,
                'object' => 'payment',
                'due_date' => $dueDate
            ])
        ]);

        $customer = Customer::factory()->fisica()->create([
            'asaas_id' => 'cus_123456'
        ]);
        $response = $this->post(route('payments.store'), [
            'customer_id' => $customer->id,
            'billing_type' => BillingType::BOLETO->value,
            'status' => PaymentStatus::PENDING->value,
            'value' => 99.99,
            'due_date' => $dueDate,
        ]);
        $payment = Payment::first();

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('payments.edit', ['payment' => $payment]));

        $this->assertSame('pay_123456', $payment->getAttribute('asaas_id'));
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function test_payment_can_be_updated(): void
    {
        $dueDate = Carbon::now()->addDay()->format('Y-m-d');

        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments/pay_123456' => Http::response([
                'id' => 'pay_123456',
                'customer' => 'cus_123456',
                'billingType' => BillingType::PIX->value,
                'status' => PaymentStatus::PENDING->value,
                'value' => 88.88,
                'due_date' => $dueDate,
                'object' => 'payment',
            ])
        ]);

        $customer = Customer::factory()->fisica()->create(['asaas_id' => 'cus_123456']);
        $payment = Payment::factory()->boleto()->create(['asaas_id' => 'pay_123456', 'customer_id' => $customer->id]);
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put(route('payments.update', ['payment' => $payment]), [
                'customer_id' => $customer->id,
                'billing_type' => BillingType::PIX->value,
                'status' => PaymentStatus::PENDING->value,
                'value' => 88.88,
                'due_date' => $dueDate,
            ]);

        $payment->refresh();

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('payments.edit', ['payment' => $payment]));

        $this->assertSame(BillingType::PIX->value, $payment->billing_type->value);
        $this->assertSame(88.88, $payment->value);
        $this->assertSame($dueDate, $payment->dueDateFormatted());
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function test_payment_can_be_deleted(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments/pay_123456' => Http::response([
                'id' => 'pay_123456',
                'deleted' => true
            ])
        ]);

        $customer = Customer::factory()->fisica()->create(['asaas_id' => 'cus_123456']);
        $payment = Payment::factory()->boleto()->create(['asaas_id' => 'pay_123456', 'customer_id' => $customer->id]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('payments.delete', ['payment' => $payment]));

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('payments.index'));

        $this->assertDatabaseEmpty('payments');
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function test_that_payment_form_using_credit_card_is_validated(): void
    {
        $this->actingAs(User::factory()->create());

        $dueDate = Carbon::now()->addDay()->format('Y-m-d');
        $customer = Customer::factory()->fisica()->create([
            'asaas_id' => 'cus_123456'
        ]);

        $response = $this->post(route('payments.store'), [
            'customer_id' => $customer->id,
            'billing_type' => BillingType::CREDIT_CARD->value,
            'status' => PaymentStatus::PENDING->value,
            'value' => 99.99,
            'due_date' => $dueDate,
            'credit_card' => [],
            'holder' => [],
        ]);

        $payment = Payment::first();
        $this->assertNull($payment);
        $response->assertSessionHasErrorsIn('payment', [
            'credit_card',
            'credit_card.holder_name',
            'credit_card.number',
            'credit_card.expiry_month',
            'credit_card.expiry_year',
            'credit_card.ccv',
            'holder',
            'holder.name',
            'holder.email',
            'holder.cpf_cnpj',
            'holder.postal_code',
            'holder.address_number',
            'holder.mobile_phone',
        ]);
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function test_payments_using_credit_card_can_be_created(): void
    {
        $this->actingAs(User::factory()->create());

        $dueDate = Carbon::now()->addDay()->format('Y-m-d');
        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments' => Http::response([
                'id' => 'pay_123456',
                'customer' => 'cus_123456',
                'billingType' => BillingType::CREDIT_CARD->value,
                'status' => PaymentStatus::PENDING->value,
                'value' => 99.99,
                'object' => 'payment',
                'due_date' => $dueDate
            ])
        ]);

        $customer = Customer::factory()->fisica()->create([
            'asaas_id' => 'cus_123456',
            'name' => 'John Doe'
        ]);
        $response = $this->post(route('payments.store'), [
            'customer_id' => $customer->id,
            'billing_type' => BillingType::CREDIT_CARD->value,
            'status' => PaymentStatus::PENDING->value,
            'value' => 99.99,
            'due_date' => $dueDate,
            'credit_card' => [
                'holder_name' => strtoupper($customer->name),
                'number' => '5162306219378829',
                'expiry_month' => '01',
                'expiry_year' => '2030',
                'ccv' => '318',
            ],
            'holder' => [
                'name' => $customer->name,
                'email' => $customer->email,
                'cpf_cnpj' => $customer->cpf_cnpj,
                'postal_code' => '88220000',
                'address_number' => '123',
                'address_complement' => null,
                'phone' => null,
                'mobile_phone' => '21999887766',
            ],
        ]);

        $payment = Payment::first();
        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('payments.edit', ['payment' => $payment]));

        $this->assertSame('pay_123456', $payment->getAttribute('asaas_id'));
    }
}
