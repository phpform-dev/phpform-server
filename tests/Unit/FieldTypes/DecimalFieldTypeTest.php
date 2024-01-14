<?php
namespace App\Tests\Unit\FieldTypes;

use App\FieldTypes\Adapters\DecimalFieldType;
use PHPUnit\Framework\TestCase;

class DecimalFieldTypeTest extends TestCase
{
    public function testGetDefaultOptionValuesAndTypes(): void
    {
        $fieldType = new DecimalFieldType();
        $this->checkParams($fieldType->getRequiredParams(), 2);
    }

    public function testGetValuesAndOptionsWithDifferentValues(): void
    {

        $tests = [ 0, 1, 2, 3, 4 ];

        foreach ($tests as $test) {
            $fieldType = new DecimalFieldType([
                [
                    'name' => 'precision',
                    'type' => 'int',
                    'value' => $test,
                ],
            ]
            );

            $this->checkParams($fieldType->getRequiredParams(), $test);
        }
    }

    public function testRightCastingFromDifferentTypes(): void {
        $tests = [
            ['0', 0],
            ['1', 1],
            ['2', 2],
            ['3', 3],
            ['4', 4],
            ['0.0', 0],
            ['1.0', 1],
            ['2.0', 2],
            ['3.0', 3],
            ['4.0', 4],
            ['0.1', 0],
            ['1.1', 1],
            ['2.1', 2],
            ['3.1', 3],
            ['4.1', 4],
            ['0.9', 0],
            ['1.9', 1],
            ['2.9', 2],
            ['3.9', 3],
            ['4.9', 4],
            ['0.01', 0],
            ['1.01', 1],
            ['2.01', 2],
            ['3.01', 3],
            ['4.01', 4],
            ['0.99', 0],
            ['1.99', 1],
            ['2.99', 2],
            ['3.99', 3],
            ['4.99', 4],
            ['0.001', 0],
            ['1.001', 1],
            ['2.001', 2],
            ['3.001', 3],
            ['4.001', 4],
            ['0.999', 0],
            ['1.999', 1],
            ['2.999', 2],
            ['3.999', 3],
            ['4.999', 4],
            ['0.0001', 0],
            ['1.0001', 1],
            ['2.0001', 2],
            ['3.0001', 3],
            ['4.0001', 4],
            ['0.9999', 0],
            ['1.9999', 1],
            ['2.9999', 2],
            ['3.9999', 3],
            ['4.9999', 4],
            ['0.00001', 0],
            ['1.00001', 1],
            ['2.00001', 2],
            ['3.00001', 3],
            ['4.00001', 4],
        ];

        foreach ($tests as $test) {
            $fieldType = new DecimalFieldType([
                [
                    'name' => 'precision',
                    'type' => 'int',
                    'value' => $test[0],
                ],
            ]);

            $this->checkParams($fieldType->getRequiredParams(), $test[1]);
        }
    }

    private function checkParams(array $params, int $value): void
    {
        $this->assertCount(3, $params);

        foreach ($params as $param) {
            if ($param['name'] === 'precision') {
                $this->assertEquals('int', $param['type']);
                $this->assertIsInt($param['value']);
                $this->assertEquals($value, $param['value']);
            }
        }
    }
}