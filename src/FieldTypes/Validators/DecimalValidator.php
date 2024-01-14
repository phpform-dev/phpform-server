<?php
namespace App\FieldTypes\Validators;

readonly class DecimalValidator implements ValidatorInterface {

    public function __construct(private int $precision = 2)
    {
    }

    public function validate($value): bool {
        if ($this->precision === null) {
            return true;
        }

        $fraction = explode('.', (string) $value);

        return isset($fraction[1]) && strlen($fraction[1]) === $this->precision;
    }

    public function getErrorMessage(): string {
        return "The value must be a decimal with {$this->precision} decimal places.";
    }
}
