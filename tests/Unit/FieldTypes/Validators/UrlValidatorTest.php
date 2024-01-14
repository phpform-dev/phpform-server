<?php

namespace App\Tests\Unit\FieldTypes\Validators;

use PHPUnit\Framework\TestCase;
use App\FieldTypes\Validators\URLValidator;

class UrlValidatorTest extends TestCase
{
    private URLValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new URLValidator();
    }

    public function testValidURL()
    {
        $this->assertTrue($this->validator->validate('http://example.com'));
        $this->assertTrue($this->validator->validate('https://example.com'));
        $this->assertTrue($this->validator->validate('https://www.example.com/path?arg=value#anchor'));
    }

    public function testInvalidURL()
    {
        $this->assertFalse($this->validator->validate('just-a-string'));
        $this->assertFalse($this->validator->validate('http://'));
        $this->assertFalse($this->validator->validate('www.example.com'));
        $this->assertFalse($this->validator->validate('example.com'));
    }
}
