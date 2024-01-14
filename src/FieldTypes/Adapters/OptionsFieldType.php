<?php
namespace App\FieldTypes\Adapters;

use App\FieldTypes\AbstractFieldType;
use App\FieldTypes\Validators;

class OptionsFieldType extends AbstractFieldType
{
    public function getRequiredValidatorClasses(): array
    {
        return [
            Validators\OptionsValidator::class,
        ];
    }
}