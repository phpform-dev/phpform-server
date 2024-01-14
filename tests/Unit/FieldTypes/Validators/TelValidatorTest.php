<?php

namespace App\Tests\Unit\FieldTypes\Validators;

use PHPUnit\Framework\TestCase;
use App\FieldTypes\Validators\TelValidator;

class TelValidatorTest extends TestCase
{
    private TelValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new TelValidator();
    }

    public function testValidPhoneNumber()
    {
        // Assuming the pattern is \+[0-9]{7,15}
        $this->assertTrue($this->validator->validate('+1234567'));
        $this->assertTrue($this->validator->validate('+123456789012345'));
    }

    public function testInvalidPhoneNumber()
    {
        $this->assertFalse($this->validator->validate('1234567')); // No leading plus
        $this->assertFalse($this->validator->validate('+1')); // Too short
        $this->assertFalse($this->validator->validate('+1234567890123456')); // Too long
        $this->assertFalse($this->validator->validate('+abcdefg')); // Non-numeric characters
        $this->assertFalse($this->validator->validate('+123-456-7890')); // Invalid characters
    }

}
