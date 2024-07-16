<?php

namespace App\Api\Asaas;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Customers extends Client
{
    use WithCrud;

    /**
     * @return string
     */
    public function objectType(): string
    {
        return 'customer';
    }

    /**
     * @return string
     */
    public function endpoint(): string
    {
        return $this->host . '/v3/customers';
    }

    /**
     * @param Customer|Model $model
     * @return array
     */
    public function mapFromModel(Model $model): array
    {
        return [
            'name' => $model->getAttribute('name'),
            'email' => $model->getAttribute('email'),
            'personType' => $model->getAttribute('person_type')->value,
            'cpfCnpj' => $model->getAttribute('cpf_cnpj'),
            'mobilePhone' => $model->getAttribute('mobile_phone'),
        ];
    }

    /**
     * @param object $asaasObject
     * @return array
     */
    public function mapToModel(object $asaasObject): array
    {
        return [
            'asaas_id' => $asaasObject->id,
        ];
    }

    /**
     * @param object $asaasObject
     * @return void
     */
    public function getObjectId(object $asaasObject): string
    {
        return $asaasObject->id;
    }
}
