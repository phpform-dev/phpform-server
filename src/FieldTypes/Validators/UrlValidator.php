<?php
namespace App\FieldTypes\Validators;

readonly class UrlValidator implements ValidatorInterface {

    public function validate($value): bool {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    public function getErrorMessage(): string {
        return "The value must be a valid URL.";
    }
}
