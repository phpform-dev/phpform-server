<?php

namespace App\Tests\Unit\FieldTypes\Validators;

use PHPUnit\Framework\TestCase;
use App\FieldTypes\Validators\DecimalValidator;

class DecimalValidatorTest extends TestCase
{
    public function testValidDecimal()
    {
        $validator = new DecimalValidator(2);

        $this->assertTrue($validator->validate('123.45'));
        $this->assertTrue($validator->validate('0.10')); // Test leading zero
        $this->assertTrue($validator->validate('54321.00')); // Test trailing zeros
    }

    public function testInvalidDecimal()
    {
        $validator = new DecimalValidator(2);

        $this->assertFalse($validator->validate('123.4')); // Only one decimal place
        $this->assertFalse($validator->validate('123.456')); // More than two decimal places
        $this->assertFalse($validator->validate('abc.def')); // Non-numeric values
        $this->assertFalse($validator->validate('123')); // No decimal places
        $this->assertFalse($validator->validate('')); // Empty string
    }

    public function testDifferentPrecision()
    {
        $validator = new DecimalValidator(3); // Testing with different precision

        $this->assertTrue($validator->validate('123.456'));
        $this->assertFalse($validator->validate('123.45')); // Less than 3 decimal places
    }
}
