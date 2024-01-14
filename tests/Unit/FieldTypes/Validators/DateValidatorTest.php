<?php

namespace App\Tests\Unit\FieldTypes\Validators;

use PHPUnit\Framework\TestCase;
use App\FieldTypes\Validators\DateValidator;

class DateValidatorTest extends TestCase
{
    private DateValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new DateValidator();
    }

    public function testValidDate()
    {
        $this->assertTrue($this->validator->validate('2022-12-22'));
        $this->assertTrue($this->validator->validate('1976-01-11'));
        $this->assertTrue($this->validator->validate('1056-12-31'));
    }

    public function testInvalidDate()
    {
        $this->assertFalse($this->validator->validate('2022-02-30')); // Invalid date
        $this->assertFalse($this->validator->validate('2022-13-01')); // Invalid month
        $this->assertFalse($this->validator->validate('202-12-10'));  // Invalid year format
        $this->assertFalse($this->validator->validate('2022/12/10')); // Invalid separator
        $this->assertFalse($this->validator->validate(''));           // Empty string
    }
}
