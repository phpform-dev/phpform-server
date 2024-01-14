<?php
namespace App\FieldTypes\Validators;

readonly class RegExpValidator implements ValidatorInterface {

    public function __construct(
        private ?string $pattern = null
    ) {
    }

    public function validate($value): bool {
        if ($this->pattern === null) {
            return true;
        }
        return preg_match($this->pattern, $value) === 1;
    }

    public function getErrorMessage(): string {
        return "The value does not match the required pattern.";
    }
}