<?php
namespace App\FieldTypes\Validators;

readonly class NumberValidator implements ValidatorInterface {

    public function __construct(
        private ?int $min = null,
        private ?int $max = null
    ) {
    }

    public function validate($value): bool {
        if (!is_numeric($value)) {
            return false;
        }

        if ($this->min === null && $this->max === null) {
            return true;
        }

        if ($this->min !== null && $value < $this->min) {
            return false;
        }

        if ($this->max !== null && $value > $this->max) {
            return false;
        }

        return true;
    }

    public function getErrorMessage(): string {
        if ($this->min === null && $this->max === null) {
            return "The value must be a number.";
        }

        if ($this->min === null) {
            return "The number must be less than or equal to {$this->max}.";
        }

        if ($this->max === null) {
            return "The number must be greater than or equal to {$this->min}.";
        }

        return "The number must be between {$this->min} and {$this->max}.";
    }
}