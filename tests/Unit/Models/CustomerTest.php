<?php

namespace Tests\Unit\Models;

use App\Enums\PersonType;
use App\Models\Customer;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    /**
     * @return void
     */
    public function test_that_cpf_cnpj_attribute_remove_only_non_numeric_characters(): void
    {
        $customer = new Customer;
        $customer->setAttribute('cpf_cnpj', '123.456.789-10');
        $this->assertEquals('12345678910', $customer->getAttribute('cpf_cnpj'));
    }

    /**
     * @return void
     */
    public function test_that_mobile_phone_attribute_remove_only_non_numeric_characters(): void
    {
        $customer = new Customer;
        $customer->setAttribute('mobile_phone', '(99) 91234-5678');
        $this->assertEquals('99912345678', $customer->getAttribute('mobile_phone'));
    }

    /**
     * @return void
     */
    public function test_that_person_type_attribute_is_casted_and_tested(): void
    {
        $customer = new Customer;
        $customer->setAttribute('person_type', PersonType::FISICA);
        $this->assertTrue($customer->fisica());
        $this->assertFalse($customer->juridica());
        $customer->setAttribute('person_type', PersonType::FISICA->value);
        $this->assertTrue($customer->fisica());
        $this->assertFalse($customer->juridica());

        $customer->setAttribute('person_type', PersonType::JURIDICA);
        $this->assertFalse($customer->fisica());
        $this->assertTrue($customer->juridica());
        $customer->setAttribute('person_type', PersonType::JURIDICA->value);
        $this->assertFalse($customer->fisica());
        $this->assertTrue($customer->juridica());
    }
}
