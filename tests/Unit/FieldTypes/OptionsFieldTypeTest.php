<?php
namespace App\Tests\Unit\FieldTypes;

use App\FieldTypes\Adapters\NumberFieldType;
use App\FieldTypes\Adapters\OptionsFieldType;
use PHPUnit\Framework\TestCase;

class OptionsFieldTypeTest extends TestCase
{
    public function testGetDefaultOptionValuesAndTypes(): void
    {
        $fieldType = new OptionsFieldType([
            [
                'name' => 'options',
                'type' => 'array',
                'value' => ['option1', 'option2'],
            ],
        ]);

        $this->assertCount(1, $fieldType->getRequiredParams());
        $this->assertIsArray($fieldType->getRequiredParams()[0]['value']);
        $this->assertEquals(['option1', 'option2'], $fieldType->getRequiredParams()[0]['value']);
    }
}