<?php
namespace App\FieldTypes\Validators;

readonly class OptionsValidator implements ValidatorInterface
{

    public function __construct(private array $options = [])
    {
    }

    public function validate($value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $option) {
            if (!in_array($option, $this->options)) {
                return false;
            }
        }

        return true;
    }

    public function getErrorMessage(): string
    {
        return "The options must be one of the following: " . implode(', ', $this->options) . ".";
    }
}