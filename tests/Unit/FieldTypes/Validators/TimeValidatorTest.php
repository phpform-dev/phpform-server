<?php

namespace App\Tests\Unit\FieldTypes\Validators;

use PHPUnit\Framework\TestCase;
use App\FieldTypes\Validators\TimeValidator;

class TimeValidatorTest extends TestCase
{
    private TimeValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new TimeValidator();
    }

    public function testValidTime()
    {
        $this->assertTrue($this->validator->validate('14:30'));
        $this->assertTrue($this->validator->validate('02:05')); // Leading zero in hours
        $this->assertTrue($this->validator->validate('2:05'));  // No leading zero in hours
        $this->assertTrue($this->validator->validate('23:59')); // Edge of valid time
        $this->assertTrue($this->validator->validate('0:00'));  // Start of valid time
    }

    public function testInvalidTime()
    {
        $this->assertFalse($this->validator->validate('24:00')); // Hour too high
        $this->assertFalse($this->validator->validate('23:60')); // Minute too high
        $this->assertFalse($this->validator->validate('14:3'));  // Minute without leading zero
        $this->assertFalse($this->validator->validate('14'));    // Missing minutes
        $this->assertFalse($this->validator->validate('14:301')); // Extra numbers
        $this->assertFalse($this->validator->validate('abc:def')); // Non-numeric
        $this->assertFalse($this->validator->validate('14.30'));  // Wrong separator
    }

}
