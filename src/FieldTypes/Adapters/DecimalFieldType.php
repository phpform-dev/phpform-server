<?php
namespace App\FieldTypes\Adapters;

use App\FieldTypes\AbstractFieldType;
use App\FieldTypes\Validators;

class DecimalFieldType extends AbstractFieldType
{
    public function getRequiredValidatorClasses(): array
    {
        return [
            Validators\NumberValidator::class,
            Validators\DecimalValidator::class,
        ];
    }
}