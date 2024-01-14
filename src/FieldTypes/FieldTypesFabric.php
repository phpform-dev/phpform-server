<?php

namespace App\FieldTypes;

class FieldTypesFabric
{
    public function getAll(): array
    {
        $classes = glob(__DIR__ . '/Adapters/*.php');
        $adapters = [];
        foreach ($classes as $class) {
            $className = 'App\FieldTypes\Adapters\\' . str_replace('.php', '', basename($class));
            $adapters[] = new $className([]);
        }
        return $adapters;
    }

    public function getOneByType(string $type, array $options = []): AbstractFieldType
    {
        $className = 'App\FieldTypes\Adapters\\' . ucfirst($type) . 'FieldType';
        return new $className($options);
    }
}