<?php

namespace App\Tests\Unit\FieldTypes\Validators;

use PHPUnit\Framework\TestCase;
use App\FieldTypes\Validators\RegExpValidator;

class RegExpValidatorTest extends TestCase
{
    public function testValidRegExp()
    {
        // Example with an email pattern
        $validator = new RegExpValidator('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/');

        $this->assertTrue($validator->validate('test@example.com'));
        $this->assertTrue($validator->validate('user.name+tag@domain.co.uk'));
    }

    public function testInvalidRegExp()
    {
        // Example with an email pattern
        $validator = new RegExpValidator('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$/');

        $this->assertFalse($validator->validate('plainaddress'));
        $this->assertFalse($validator->validate('@no-local-part.com'));
        $this->assertFalse($validator->validate('username@domain..com'));
    }

    public function testDifferentPatterns()
    {
        // Example with a numeric pattern
        $numericValidator = new RegExpValidator('/^\d+$/');
        $this->assertTrue($numericValidator->validate('12345'));
        $this->assertFalse($numericValidator->validate('abc123'));

        // Example with a specific format (e.g., date in YYYY-MM-DD)
        $dateValidator = new RegExpValidator('/^\d{4}-\d{2}-\d{2}$/');
        $this->assertTrue($dateValidator->validate('2022-01-01'));
        $this->assertFalse($dateValidator->validate('01-01-2022'));
    }
}
