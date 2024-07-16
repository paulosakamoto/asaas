<?php

namespace App\Api\Asaas;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Payments extends Client
{
    use WithCrud;

    /**
     * @return string
     */
    public function objectType(): string
    {
        return 'payment';
    }

    /**
     * @return string
     */
    public function endpoint(): string
    {
        return $this->host . '/v3/payments';
    }

    /**
     * @param Payment|Model $model
     * @return array
     */
    public function mapFromModel(Model $model): array
    {
        return [
            'customer' => $model->customer->getAsaasId(),
            'billingType' => $model->getAttribute('billing_type')->value,
            'value' => $model->getAttribute('value'),
            'dueDate' => $model->getAttribute('due_date')->format('Y-m-d'),
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
            'status' => $asaasObject->status,
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
