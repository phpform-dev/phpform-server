<?php

namespace App\Tests\Unit\FieldTypes\Validators;

use PHPUnit\Framework\TestCase;
use App\FieldTypes\Validators\NumberValidator;

class NumberValidatorTest extends TestCase
{
    public function testValidNumber()
    {
        $validator = new NumberValidator(10, 20); // Range: 10 to 20

        $this->assertTrue($validator->validate(10));
        $this->assertTrue($validator->validate(15));
        $this->assertTrue($validator->validate(20));
        $this->assertTrue($validator->validate('15'));

    }

    public function testInvalidNumber()
    {
        $validator = new NumberValidator(10, 20); // Range: 10 to 20

        $this->assertFalse($validator->validate(9));  // Below the range
        $this->assertFalse($validator->validate(21)); // Above the range
        $this->assertFalse($validator->validate(null)); // Null value
    }

    public function testBoundaryConditions()
    {
        $validator = new NumberValidator(10, 10); // Testing with same min and max

        $this->assertTrue($validator->validate(10)); // Equal to the only allowed value
        $this->assertFalse($validator->validate(11)); // Just above the range
        $this->assertFalse($validator->validate(9));  // Just below the range
    }
}
