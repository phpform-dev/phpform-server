<?php
namespace App\Service;

use App\FieldTypes\AbstractFieldType;
use App\FieldTypes\FieldTypesFabric;

readonly class FormFieldTypeService {
    public function __construct(private FieldTypesFabric $fabric)
    {
    }

    public function getAll(): array
    {
        return array_map(fn($type) => $type->exportConfig(), $this->fabric->getAll());
    }

    public function getTypeNames(): array
    {
        return array_map(fn($type) => $type->getType(), $this->fabric->getAll());
    }

    public function getOneByType(string $type, array $options): AbstractFieldType
    {
        return $this->fabric->getOneByType($type, $options);
    }

    public function getValidationParams(string $type, array $options): array
    {
        return $this->fabric->getOneByType($type, $options)->exportValidationConfig();
    }
}