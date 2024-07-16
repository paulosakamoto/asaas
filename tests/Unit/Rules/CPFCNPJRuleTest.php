<?php

namespace Tests\Unit\Rules;

use App\Rules\CPFCNPJRule;
use Tests\TestCase;

/**
 *
 */
class CPFCNPJRuleTest extends TestCase
{
    /**
     * @var CPFCNPJRule
     */
    protected $rule;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new CPFCNPJRule;
    }

    public function test_validation_must_fail_with_invalid_number_size()
    {
        $lastMessage = 'fails';
        $this->rule->validate('cpf_cnpj', '123', function ($message) use (&$lastMessage) {
            $lastMessage = $message;
        });
        $this->assertEquals('The :attribute must be 11 numbers or 14 numbers.', $lastMessage);
    }

    public function test_validation_must_fail_with_non_numeric_characters()
    {
        $numbers = [
            '88.897.217/0001-97',
            '821.975.010-38',
        ];
        foreach ($numbers as $number) {
            $lastMessage = 'fails';
            $this->rule->validate('cpf_cnpj', $number, function ($message) use (&$lastMessage) {
                $lastMessage = $message;
            });
            $this->assertEquals('The :attribute must have only numbers.', $lastMessage);
        }
    }

    public function test_validation_must_pass_with_valid_cpf_cnpj_numbers()
    {
        $numbers = [
            '12041650000164',
            '62771049000103',
            '08180155000169',
            '09675010000100',

            '60355652048',
            '21287326030',
            '88328225042',
            '00361860072',
        ];
        foreach ($numbers as $number) {
            $lastMessage = 'ok';
            $this->rule->validate('cpf_cnpj', $number, function ($message) use (&$lastMessage) {
                $lastMessage = $message;
            });
            $this->assertEquals('ok', $lastMessage);
        }
    }
}
