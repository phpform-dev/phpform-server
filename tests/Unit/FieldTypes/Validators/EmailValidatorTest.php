<?php

namespace App\Tests\Unit\FieldTypes\Validators;

use PHPUnit\Framework\TestCase;
use App\FieldTypes\Validators\EmailValidator;

class EmailValidatorTest extends TestCase
{
    private EmailValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new EmailValidator();
    }

    public function testValidEmail()
    {
        $this->assertTrue($this->validator->validate('example@example.com'));
        $this->assertTrue($this->validator->validate('user.name+tag@domain.co.uk'));
    }

    public function testInvalidEmail()
    {
        $this->assertFalse($this->validator->validate('plainaddress'));
        $this->assertFalse($this->validator->validate('@no-local-part.com'));
        $this->assertFalse($this->validator->validate('Outlook Contact <outlook-contact@domain.com>'));
        $this->assertFalse($this->validator->validate('no-at-sign.net'));
        $this->assertFalse($this->validator->validate('no-tld@domain')); // Missing top-level domain (TLD)
        $this->assertFalse($this->validator->validate(';beginning-semicolon@semicolon.com'));
        $this->assertFalse($this->validator->validate('middle-semicolon@domain.co;m'));
        $this->assertFalse($this->validator->validate('trailing-semicolon@domain.com;'));
        $this->assertFalse($this->validator->validate('username@domain.com@anotherdomain.com'));
        $this->assertFalse($this->validator->validate('username@domain..com')); // Double dot after @
    }
}
