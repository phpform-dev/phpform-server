<?php
namespace App\FieldTypes\Validators;

readonly class LengthValidator implements ValidatorInterface {

    public function __construct(
        private ?int $minLength = null,
        private ?int $maxLength = null
    ) {
    }

    public function validate($value): bool {
        $length = mb_strlen($value);
        
        if ($this->minLength !== null && $length < $this->minLength) {
            return false;
        }

        if ($this->maxLength !== null && $length > $this->maxLength) {
            return false;
        }

        return true;
    }

    public function getErrorMessage(): string {
        return "The length must be between {$this->minLength} and {$this->maxLength}.";
    }
}