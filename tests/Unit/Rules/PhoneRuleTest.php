<?php

namespace Tests\Unit\Rules;

use App\Rules\PhoneRule;
use Tests\TestCase;

/**
 *
 */
class PhoneRuleTest extends TestCase
{
    /**
     * @var PhoneRule
     */
    protected $rule;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new PhoneRule;
    }

    public function test_validation_must_fail_with_invalid_phone_size()
    {
        $lastMessage = 'fails';
        $this->rule->validate('phone', '479988776', function ($message) use (&$lastMessage) {
            $lastMessage = $message;
        });
        $this->assertEquals('The :attribute must be 10 or 11 numbers.', $lastMessage);
    }

    public function test_validation_must_fail_with_non_numeric_characters()
    {
        $lastMessage = 'fails';
        $this->rule->validate('phone', '(99) 9988-7766', function ($message) use (&$lastMessage) {
            $lastMessage = $message;
        });
        $this->assertEquals('The :attribute must have only numbers.', $lastMessage);
    }

    public function test_validation_must_pass_with_valid_phone_number()
    {
        $numbers = [
            '2199887766',
            '47999887766',
        ];
        foreach ($numbers as $number) {
            $lastMessage = 'ok';
            $this->rule->validate('phone', $number, function ($message) use (&$lastMessage) {
                $lastMessage = $message;
            });
            $this->assertEquals('ok', $lastMessage);
        }
    }
}
