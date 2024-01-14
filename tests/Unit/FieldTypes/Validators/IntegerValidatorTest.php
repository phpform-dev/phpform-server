<?php

namespace App\Tests\Unit\FieldTypes\Validators;

use PHPUnit\Framework\TestCase;
use App\FieldTypes\Validators\IntegerValidator;

class IntegerValidatorTest extends TestCase
{
    private IntegerValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new IntegerValidator();
    }

    public function testValidInteger()
    {
        $this->assertTrue($this->validator->validate(123));
        $this->assertTrue($this->validator->validate(0));
        $this->assertTrue($this->validator->validate(-123));
    }

    public function testInvalidInteger()
    {
        $this->assertFalse($this->validator->validate(123.45)); // Float
        $this->assertFalse($this->validator->validate("123")); // String
        $this->assertFalse($this->validator->validate(null)); // Null
        $this->assertFalse($this->validator->validate(array())); // Array
        $this->assertFalse($this->validator->validate(new \stdClass())); // Object
    }
}
