<?php

namespace App;

trait HasAsaasId
{
    /**
     * @param string $asaasId
     * @return $this
     */
    public function setAsaasId(string $asaasId): static
    {
        return $this->setAttribute('asaas_id', $asaasId);
    }

    /**
     * @return string
     */
    public function getAsaasId(): string
    {
        return $this->getAttribute('asaas_id');
    }
}
