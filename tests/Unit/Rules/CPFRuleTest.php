<?php

namespace Tests\Unit\Rules;

use App\Rules\CPFRule;
use Tests\TestCase;

/**
 *
 */
class CPFRuleTest extends TestCase
{
    /**
     * @var CPFRule
     */
    protected $rule;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new CPFRule;
    }

    public function test_validation_must_fail_with_invalid_cpf_size()
    {
        $lastMessage = 'fails';
        $this->rule->validate('cpf', '123', function ($message) use (&$lastMessage) {
            $lastMessage = $message;
        });
        $this->assertEquals('The :attribute must be 11 numbers.', $lastMessage);
    }

    public function test_validation_must_fail_with_invalid_cpf_number()
    {
        $lastMessage = 'fails';
        $this->rule->validate('cpf', '12345678901', function ($message) use (&$lastMessage) {
            $lastMessage = $message;
        });
        $this->assertEquals('The :attribute must be a valid CPF number.', $lastMessage);
    }

    public function test_validation_must_fail_with_non_numeric_characters()
    {
        $lastMessage = 'fails';
        $this->rule->validate('cpf', '821.975.010-38', function ($message) use (&$lastMessage) {
            $lastMessage = $message;
        });
        $this->assertEquals('The :attribute must have only numbers.', $lastMessage);
    }

    public function test_validation_must_pass_with_valid_cpf_number()
    {
        $numbers = [
            '60355652048',
            '21287326030',
            '88328225042',
            '00361860072',
        ];
        foreach ($numbers as $number) {
            $lastMessage = 'ok';
            $this->rule->validate('cpf', $number, function ($message) use (&$lastMessage) {
                $lastMessage = $message;
            });
            $this->assertEquals('ok', $lastMessage);
        }
    }
}
