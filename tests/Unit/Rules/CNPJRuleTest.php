<?php

namespace Tests\Unit\Rules;

use App\Rules\CNPJRule;
use Tests\TestCase;

/**
 *
 */
class CNPJRuleTest extends TestCase
{
    /**
     * @var CNPJRule
     */
    protected $rule;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new CNPJRule;
    }

    public function test_validation_must_fail_with_invalid_cpf_size()
    {
        $lastMessage = 'fails';
        $this->rule->validate('cnpj', '123', function ($message) use (&$lastMessage) {
            $lastMessage = $message;
        });
        $this->assertEquals('The :attribute must be 14 numbers.', $lastMessage);
    }

    public function test_validation_must_fail_with_invalid_cnpj_number()
    {
        $lastMessage = 'fails';
        $this->rule->validate('cnpj', '12345678901234', function ($message) use (&$lastMessage) {
            $lastMessage = $message;
        });
        $this->assertEquals('The :attribute must be a valid CNPJ number.', $lastMessage);
    }

    public function test_validation_must_fail_with_non_numeric_characters()
    {
        $lastMessage = 'fails';
        $this->rule->validate('cnpj', '88.897.217/0001-97', function ($message) use (&$lastMessage) {
            $lastMessage = $message;
        });
        $this->assertEquals('The :attribute must have only numbers.', $lastMessage);
    }

    public function test_validation_must_pass_with_valid_cnpj_number()
    {
        $numbers = [
            '12041650000164',
            '62771049000103',
            '08180155000169',
            '09675010000100',
        ];
        foreach ($numbers as $number) {
            $lastMessage = 'ok';
            $this->rule->validate('cnpj', $number, function ($message) use (&$lastMessage) {
                $lastMessage = $message;
            });
            $this->assertEquals('ok', $lastMessage);
        }
    }
}
