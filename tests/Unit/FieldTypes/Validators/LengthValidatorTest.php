<?php
namespace App\Tests\Unit\FieldTypes\Validators;

use PHPUnit\Framework\TestCase;
use App\FieldTypes\Validators\LengthValidator;

class LengthValidatorTest extends TestCase
{
    public function testValidLength()
    {
        $validator = new LengthValidator(3, 5); // Min length 3, Max length 5

        $this->assertTrue($validator->validate('abc'));
        $this->assertTrue($validator->validate('abcd'));
        $this->assertTrue($validator->validate('abcde'));
    }

    public function testInvalidLength()
    {
        $validator = new LengthValidator(3, 5); // Min length 3, Max length 5

        $this->assertFalse($validator->validate('ab')); // Too short
        $this->assertFalse($validator->validate('abcdef')); // Too long
        $this->assertFalse($validator->validate('')); // Empty string
    }

    public function testBoundaryConditions()
    {
        $validator = new LengthValidator(0, 5); // Testing with zero min length

        $this->assertTrue($validator->validate('')); // Empty string is valid
        $this->assertTrue($validator->validate('abcde')); // Max length
        $this->assertFalse($validator->validate('abcdef')); // Exceeding max length
    }
}
