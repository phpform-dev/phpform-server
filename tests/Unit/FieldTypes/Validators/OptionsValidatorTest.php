<?php

namespace App\Tests\Unit\FieldTypes\Validators;

use App\FieldTypes\Validators\OptionsValidator;
use PHPUnit\Framework\TestCase;

class OptionsValidatorTest extends TestCase
{
    private OptionsValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new OptionsValidator(['option1', 'option2']);
    }

    public function testValidOptions()
    {
        $this->assertTrue($this->validator->validate(['option1', 'option2']));
        $this->assertTrue($this->validator->validate(['option1']));
        $this->assertTrue($this->validator->validate([]));
    }

    public function testInvalidOptions()
    {
        $this->assertFalse($this->validator->validate('option1')); // Not an array
        $this->assertFalse($this->validator->validate(['option3'])); // Invalid option
        $this->assertFalse($this->validator->validate(['option1', 'option3'])); // Invalid option
    }
}
