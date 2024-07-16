<?php

namespace App\Api\Asaas;

use Illuminate\Http\Client\RequestException;
use Exception;

/**
 *
 */
trait WithCrud
{
    /**
     * @param array $data
     * @return null|object
     * @throws RequestException
     * @throws Exception
     */
    public function create(array $data)
    {
        return $this->parseResponse($this->http()->post('{+endpoint}', $data), 'object', $this->objectType());
    }

    /**
     * @param string $id
     * @param array $data
     * @return object|null
     * @throws RequestException
     * @throws Exception
     */
    public function update(string $id, array $data)
    {
        return $this->parseResponse($this->http()->put("{+endpoint}/{$id}", $data), 'object', $this->objectType());
    }

    /**
     * @param string $id
     * @return object|null
     * @throws RequestException
     * @throws Exception
     */
    public function fetchOne(string $id)
    {
        return $this->parseResponse($this->http()->get("{+endpoint}/{$id}"), 'id', $this->objectType());
    }

    /**
     * @return object|null
     * @throws RequestException
     * @throws Exception
     */
    public function fetch()
    {
        return $this->parseResponse($this->http()->get('{+endpoint}'), 'data', 'list');
    }

    /**
     * @param string $id
     * @return object|null
     * @throws RequestException
     * @throws Exception
     */
    public function delete(string $id)
    {
        return $this->parseResponse($this->http()->delete("{+endpoint}/{$id}"), 'id');
    }

    /**
     * @param string $id
     * @return object|null
     * @throws RequestException
     * @throws Exception
     */
    public function restore(string $id)
    {
        return $this->parseResponse($this->http()->post("{+endpoint}/{$id}/restore"), 'object', $this->objectType());
    }
}
