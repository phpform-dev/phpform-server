<?php

namespace App\Tests\Unit\FieldTypes\Validators;

use PHPUnit\Framework\TestCase;
use App\FieldTypes\Validators\InValidator;

class InValidatorTest extends TestCase
{
    private InValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new InValidator(['apple', 'banana', 'orange']);
    }

    public function testValueInSet()
    {
        $this->assertTrue($this->validator->validate('apple'));
        $this->assertTrue($this->validator->validate('banana'));
    }

    public function testValueNotInSet()
    {
        $this->assertFalse($this->validator->validate('pear'));
        $this->assertFalse($this->validator->validate('grape'));
    }

    public function testInvalidType()
    {
        $this->assertFalse($this->validator->validate(123)); // Integer
        $this->assertFalse($this->validator->validate([])); // Array
    }
}
