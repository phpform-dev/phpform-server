<?php
namespace App\Tests\Unit\FieldTypes;

use App\FieldTypes\Adapters\NumberFieldType;
use PHPUnit\Framework\TestCase;

class NumberFieldTypeTest extends TestCase
{
    public function testGetDefaultOptionValuesAndTypes(): void
    {
        $fieldType = new NumberFieldType();
        $this->checkParams($fieldType->getRequiredParams(), [null, null]);
    }

    public function testGetValuesAndOptionsWithDifferentValues(): void
    {

        $tests = [
            [0, 1000],
            [567, 9999],
            [345, 10000000000]
        ];

        foreach ($tests as $test) {
            $fieldType = new NumberFieldType(
                [
                    [
                        'name' => 'min',
                        'type' => 'int',
                        'value' => $test[0],
                    ],
                    [
                        'name' => 'max',
                        'type' => 'int',
                        'value' => $test[1],
                    ]
                ]
            );

            $this->checkParams($fieldType->getRequiredParams(), $test);
        }
    }

    public function testRightCastingFromDifferentTypes(): void {
        $tests = [
            [
                ['0', '1000'],
                [0, 1000],
            ],
            [
                ['abc', '1bnm'],
                [0, 1],
            ]
        ];

        foreach ($tests as $test) {
            $fieldType = new NumberFieldType([
                [
                    'name' => 'min',
                    'type' => 'int',
                    'value' => $test[0][0],
                ],
                [
                    'name' => 'max',
                    'type' => 'int',
                    'value' => $test[0][1],
                ]
            ]);

            $this->checkParams($fieldType->getRequiredParams(), $test[1]);
        }
    }

    private function checkParams(array $params, array $values): void
    {
        $this->assertCount(2, $params);

        $names = ['min', 'max'];

        foreach ($params as $key => $param) {
            $this->assertEquals('int', $param['type']);
            $this->assertEquals($names[$key], $param['name']);
            $this->assertEquals($values[$key], $param['value']);
        }
    }
}