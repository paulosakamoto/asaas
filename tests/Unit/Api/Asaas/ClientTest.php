<?php

namespace Tests\Unit\Api\Asaas;

use App\Api\Asaas\Customers;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 *
 */
class ClientTest extends TestCase
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
     */
    public function test_that_endpoint_is_valid(): void
    {
        $this->assertEquals('https://sandbox.asaas.com/api/v3/customers', $this->client->endpoint());
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_unauthorized_response_throws_exception(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers/123456' => Http::response(null, 401)
        ]);
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('HTTP request returned status code 401');
        $this->client->fetchOne('123456');
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_empty_response_throws_exception(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers/123456' => Http::response()
        ]);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The response is empty');
        $this->client->fetchOne('123456');
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_response_with_invalid_object_key_throws_exception(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers/123456' => Http::response([
                'foo' => 'bar'
            ])
        ]);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The response contains an invalid object');
        $this->client->fetchOne('123456');
    }

    /**
     * @return void
     * @throws RequestException
     */
    public function test_that_response_with_invalid_object_type_throws_exception(): void
    {
        Http::fake([
            'https://sandbox.asaas.com/api/v3/customers/123456' => Http::response([
                'id' => '123456',
                'object' => 'payment',
            ])
        ]);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The response contains an invalid type');
        $this->client->fetchOne('123456');
    }

    /**
     * @return void
     */
    public function test_can_get_object_id(): void
    {
        $this->assertEquals('cus_123456', $this->client->getObjectId((object)[
            'id' => 'cus_123456',
            'object' => 'customer',
        ]));
    }
}
