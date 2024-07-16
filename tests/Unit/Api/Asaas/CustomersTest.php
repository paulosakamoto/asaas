<?php

namespace Tests\Unit\Api\Asaas;

use App\Api\Asaas\Customers;
use App\Enums\PersonType;
use App\Models\Customer;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 *
 */
class CustomersTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Customers
     */
    protected $client;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->client = $this->app->make(Customers::class);
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_fetch_single_record(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers/123456' => Http::response([
                'id' => '123456',
                'name' => 'John Doe',
                'object' => 'customer',
            ])
        ]);
        $response = $this->client->fetchOne('123456');
        $this->assertEquals('123456', data_get($response, 'id'));
        $this->assertEquals('John Doe', data_get($response, 'name'));
        $this->assertEquals('customer', data_get($response, 'object'));
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_fetch_data(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers' => Http::response([
                'object' => 'list',
                'data' => [
                    [
                        'id' => '123456',
                        'object' => 'customer',
                    ]
                ]
            ])
        ]);
        $response = $this->client->fetch();
        $this->assertEquals('list', data_get($response, 'object'));
        $this->assertEquals('123456', data_get($response, 'data.0.id'));
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_create_record(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers' => Http::response([
                'id' => '123456',
                'name' => 'John Doe',
                'object' => 'customer',
            ])
        ]);
        $response = $this->client->create([
            'id' => '123456',
            'name' => 'John Doe',
        ]);
        $this->assertEquals('customer', data_get($response, 'object'));
        $this->assertEquals('123456', data_get($response, 'id'));
        $this->assertEquals('John Doe', data_get($response, 'name'));
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_update_record(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers/123456' => Http::response([
                'id' => '123456',
                'name' => 'John Doe',
                'object' => 'customer',
            ])
        ]);
        $response = $this->client->update('123456', [
            'name' => 'John Doe',
        ]);
        $this->assertEquals('customer', data_get($response, 'object'));
        $this->assertEquals('123456', data_get($response, 'id'));
        $this->assertEquals('John Doe', data_get($response, 'name'));
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_delete_record(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers/123456' => Http::response([
                'id' => '123456',
                'deleted' => true
            ])
        ]);
        $response = $this->client->delete('123456');
        $this->assertEquals('123456', data_get($response, 'id'));
        $this->assertEquals(true, data_get($response, 'deleted'));
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_client_can_restore_record(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers/123456/restore' => Http::response([
                'id' => '123456',
                'name' => 'John Doe',
                'object' => 'customer',
            ])
        ]);
        $response = $this->client->restore('123456');
        $this->assertEquals('customer', data_get($response, 'object'));
        $this->assertEquals('123456', data_get($response, 'id'));
        $this->assertEquals('John Doe', data_get($response, 'name'));
    }

    /**
     * @return void
     */
    public function test_that_returns_the_object_type(): void
    {
        $this->assertEquals('customer', $this->client->objectType());
    }

    /**
     * @return void
     */
    public function test_can_map_model_attributes_to_asaas_format(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'person_type' => PersonType::FISICA->value,
            'cpf_cnpj' => '12345678901',
            'mobile_phone' => '11912345678',
        ]);
        $this->assertEquals([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'personType' => PersonType::FISICA->value,
            'cpfCnpj' => '12345678901',
            'mobilePhone' => '11912345678',
        ], $this->client->mapFromModel($customer));
    }
}
