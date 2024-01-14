<?php
namespace App\FieldTypes\Adapters;

use App\FieldTypes\AbstractFieldType;
use App\FieldTypes\Validators;

class TelFieldType extends AbstractFieldType
{
    public function getRequiredValidatorClasses(): array
    {
        return [
            Validators\TelValidator::class,
        ];
    }
}