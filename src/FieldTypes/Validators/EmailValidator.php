<?php
namespace App\FieldTypes\Validators;

readonly class EmailValidator implements ValidatorInterface {

    public function validate($value): bool {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function getErrorMessage(): string {
        return "The value must be a valid email address.";
    }
}
