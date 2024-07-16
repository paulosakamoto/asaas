<?php

namespace App\Api\Asaas;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client as GuzzleClient;

/**
 *
 */
abstract class Client
{
    /**
     * @var string
     */
    private string $accessToken;

    /**
     * @var string
     */
    protected string $host;

    /**
     * @param string $accessToken
     * @param string $host
     */
    public function __construct(string $accessToken, string $host)
    {
        $this->accessToken = $accessToken;
        $this->host = $host;
    }

    /**
     * @return PendingRequest|GuzzleClient
     */
    protected function http()
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'access_token' => $this->accessToken
        ])->withUrlParameters([
            'host' => $this->host,
            'endpoint' => $this->endpoint()
        ]);
    }

    /**
     * @return string
     */
    public abstract function endpoint(): string;

    /**
     * @return string
     */
    public abstract function objectType(): string;

    /**
     * @param Model $model
     * @return array
     */
    public abstract function mapFromModel(Model $model): array;

    /**
     * @param object $asaasObject
     * @return array
     */
    public abstract function mapToModel(object $asaasObject): array;

    /**
     * @param object $asaasObject
     * @return void
     */
    public abstract function getObjectId(object $asaasObject): string;

    /**
     * @param Response $response
     * @param string $expectedKey
     * @param string|null $objectType
     * @return object
     * @throws RequestException
     */
    protected function parseResponse(Response $response, string $expectedKey, string $objectType = null)
    {
        $response->throwIfClientError();
        $object = $response->object();
        if ($object === null) {
            throw new Exception('The response is empty');
        }
        if (!property_exists($object, $expectedKey) || ($objectType !== null && !property_exists($object, 'object'))) {
            throw new Exception('The response contains an invalid object');
        }
        if ($objectType !== null && $object->object !== $objectType) {
            throw new Exception('The response contains an invalid type');
        }
        return $object;
    }
}
