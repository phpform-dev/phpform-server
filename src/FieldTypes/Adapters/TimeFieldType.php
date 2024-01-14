<?php
namespace App\FieldTypes\Adapters;

use App\FieldTypes\AbstractFieldType;
use App\FieldTypes\Validators;

class TimeFieldType extends AbstractFieldType
{
    public function getRequiredValidatorClasses(): array
    {
        return [
            Validators\TimeValidator::class,
        ];
    }
}