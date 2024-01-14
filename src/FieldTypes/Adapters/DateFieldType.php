<?php
namespace App\FieldTypes\Adapters;

use App\FieldTypes\AbstractFieldType;
use App\FieldTypes\Validators;

class DateFieldType extends AbstractFieldType
{
    public function getRequiredValidatorClasses(): array
    {
        return [
            Validators\DateValidator::class,
        ];
    }
}