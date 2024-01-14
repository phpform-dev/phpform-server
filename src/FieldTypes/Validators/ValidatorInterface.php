<?php
namespace App\FieldTypes\Validators;

interface ValidatorInterface {
    public function validate($value): bool;
    public function getErrorMessage(): string;
}