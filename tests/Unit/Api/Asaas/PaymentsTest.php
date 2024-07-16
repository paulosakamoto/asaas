<?php

namespace Tests\Unit\Api\Asaas;

use App\Api\Asaas\Payments;
use App\Enums\BillingType;
use App\Enums\PersonType;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 *
 */
class PaymentsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Payments
     */
    protected $client;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->client = $this->app->make(Payments::class);
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_fetch_single_record(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments/pay_123456' => Http::response([
                'id' => 'pay_123456',
                'customer' => 'cus_123456',
                'dueDate' => '2024-06-06',
                'value' => 99.99,
                'object' => 'payment',
                'billingType' => BillingType::BOLETO->value,
            ])
        ]);
        $response = $this->client->fetchOne('pay_123456');
        $this->assertEquals('pay_123456', data_get($response, 'id'));
        $this->assertEquals('cus_123456', data_get($response, 'customer'));
        $this->assertEquals('2024-06-06', data_get($response, 'dueDate'));
        $this->assertEquals(99.99, data_get($response, 'value'));
        $this->assertEquals(BillingType::BOLETO->value, data_get($response, 'billingType'));
        $this->assertEquals('payment', data_get($response, 'object'));
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_fetch_data(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments' => Http::response([
                'object' => 'list',
                'data' => [
                    [
                        'id' => 'pay_123456',
                        'customer' => 'cus_123456',
                        'dueDate' => '2024-06-06',
                        'value' => 99.99,
                        'object' => 'payment',
                        'billingType' => BillingType::BOLETO->value,
                    ]
                ]
            ])
        ]);
        $response = $this->client->fetch();
        $this->assertEquals('list', data_get($response, 'object'));
        $this->assertEquals('pay_123456', data_get($response, 'data.0.id'));
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_create_record(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments' => Http::response([
                'id' => 'pay_123456',
                'customer' => 'cus_123456',
                'dueDate' => '2024-06-06',
                'value' => 99.99,
                'object' => 'payment',
                'billingType' => BillingType::BOLETO->value,
            ])
        ]);
        $response = $this->client->create([
            'customer' => 'cus_123456',
            'dueDate' => '2024-06-06',
            'value' => 99.99,
            'object' => 'payment',
            'billingType' => BillingType::BOLETO->value,
        ]);
        $this->assertEquals('pay_123456', data_get($response, 'id'));
        $this->assertEquals('cus_123456', data_get($response, 'customer'));
        $this->assertEquals('2024-06-06', data_get($response, 'dueDate'));
        $this->assertEquals(99.99, data_get($response, 'value'));
        $this->assertEquals(BillingType::BOLETO->value, data_get($response, 'billingType'));
        $this->assertEquals('payment', data_get($response, 'object'));
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_update_record(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments/pay_123456' => Http::response([
                'id' => 'pay_123456',
                'customer' => 'cus_123456',
                'dueDate' => '2025-01-01',
                'value' => 88.88,
                'object' => 'payment',
                'billingType' => BillingType::PIX->value,
            ])
        ]);
        $response = $this->client->update('pay_123456', [
            'id' => 'pay_123456',
            'customer' => 'cus_123456',
            'dueDate' => '2025-01-01',
            'value' => 88.88,
            'billingType' => BillingType::PIX->value,
        ]);
        $this->assertEquals('pay_123456', data_get($response, 'id'));
        $this->assertEquals('cus_123456', data_get($response, 'customer'));
        $this->assertEquals('2025-01-01', data_get($response, 'dueDate'));
        $this->assertEquals(88.88, data_get($response, 'value'));
        $this->assertEquals(BillingType::PIX->value, data_get($response, 'billingType'));
        $this->assertEquals('payment', data_get($response, 'object'));
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_delete_record(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments/pay_123456' => Http::response([
                'id' => 'pay_123456',
                'deleted' => true
            ])
        ]);
        $response = $this->client->delete('pay_123456');
        $this->assertEquals('pay_123456', data_get($response, 'id'));
        $this->assertEquals(true, data_get($response, 'deleted'));
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_restore_record(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/payments/pay_123456/restore' => Http::response([
                'id' => 'pay_123456',
                'customer' => 'cus_123456',
                'dueDate' => '2025-01-01',
                'value' => 88.88,
                'object' => 'payment',
                'billingType' => BillingType::PIX->value,
            ])
        ]);
        $response = $this->client->restore('pay_123456');
        $this->assertEquals('pay_123456', data_get($response, 'id'));
        $this->assertEquals('cus_123456', data_get($response, 'customer'));
        $this->assertEquals('2025-01-01', data_get($response, 'dueDate'));
        $this->assertEquals(88.88, data_get($response, 'value'));
        $this->assertEquals(BillingType::PIX->value, data_get($response, 'billingType'));
        $this->assertEquals('payment', data_get($response, 'object'));
    }

    /**
     * @return void
     */
    public function test_that_returns_the_object_type(): void
    {
        $this->assertEquals('payment', $this->client->objectType());
    }

    /**
     * @return void
     */
    public function test_can_map_model_attributes_to_asaas_format(): void
    {
        $customer = Customer::factory()->fisica()->create();
        $payment = Payment::factory()->create([
            'customer_id' => $customer->id,
            'due_date' => '2025-01-01',
            'value' => 88.88,
            'billing_type' => BillingType::PIX->value,
        ]);
        $this->assertEquals([
            'customer' => $customer->getAsaasId(),
            'billingType' => BillingType::PIX->value,
            'value' => 88.88,
            'dueDate' => '2025-01-01',
        ], $this->client->mapFromModel($payment));
    }
}
