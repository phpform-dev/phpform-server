<?php
namespace App\FieldTypes\Validators;

readonly class InValidator implements ValidatorInterface {

    public function __construct(private array $allowedValues = [])
    {
    }

    public function validate($value): bool {
        return in_array($value, $this->allowedValues, true);
    }

    public function getErrorMessage(): string {
        $allowedValuesString = implode(', ', $this->allowedValues);
        return "The value must be one of the following: {$allowedValuesString}.";
    }
}
