<?php
namespace App\FieldTypes;

use App\FieldTypes\Validators\ValidatorInterface;
use ReflectionClass;

abstract class AbstractFieldType
{
    /**
     * @var ValidatorInterface[] $validators
     */
    protected array $validators = [];

    protected array $validationErrors = [];

    protected array $requiredParams = [];

    public function __construct(
        array $requiredParamDefaultValues = []
    )
    {
        foreach ($this->getRequiredValidatorClasses() as $validatorClass) {
            try {
                $reflection = new ReflectionClass($validatorClass);
                $constructor = $reflection->getConstructor();
                $validatorParams = [];
                if ($constructor) {
                    $requiredParams = $constructor->getParameters();
                    foreach ($requiredParams as $param) {
                        $paramName = $param->getName();
                        $foundList = array_filter($requiredParamDefaultValues, fn($e) => $e['name'] === $paramName);
                        if (count($foundList) > 0) {
                            $paramValue = array_values($foundList)[0]['value'];
                        } else {
                            $paramValue = null;
                        }

                        if ($paramValue === null && $param->isDefaultValueAvailable()) {
                            $paramValue = $param->getDefaultValue();
                        }

                        if ($paramValue !== null) {
                            switch ($param->getType()->getName()) {
                                case 'int':
                                    $paramValue = (int)$paramValue;
                                    break;
                                case 'float':
                                    $paramValue = (float)$paramValue;
                                    break;
                                case 'bool':
                                    $paramValue = (bool)$paramValue;
                                    break;
                                case 'string':
                                    $paramValue = (string)$paramValue;
                                    break;
                                case 'array':
                                    $paramValue = (array)$paramValue;
                                    break;
                            }
                        }

                        $this->requiredParams[] = $validatorParams[] = [
                            'name' => $paramName,
                            'type' => $param->getType()->getName(),
                            'value' => $paramValue,
                        ];
                    }
                }
                $this->validators[] = new $validatorClass(...array_map(fn($param) => $param['value'], $validatorParams));
            } catch (\ReflectionException $e) {
                throw new \RuntimeException(sprintf('Validator class %s not found', $validatorClass));
            }
        }
    }

    public function getValidators(): array
    {
        return $this->validators;
    }

    public function getRequiredParams(): array
    {
        return $this->requiredParams;
    }

    public function exportConfig(): array
    {
        return [
            'type' => $this->getType(),
            'params' => $this->getRequiredParams(),
        ];
    }

    public function exportValidationConfig(): array
    {
        return $this->exportConfig()['params'];
    }

    public function validate(mixed $value): bool
    {
        foreach ($this->validators as $validator) {
            if (!$validator->validate($value)) {
                $this->validationErrors[] = $validator->getErrorMessage();
                return false;
            }
        }

        return true;
    }

    abstract protected function getRequiredValidatorClasses(): array;

    public function getType(): string {
        $reflection = new ReflectionClass($this);
        return mb_strtolower(str_replace('FieldType', '', $reflection->getShortName()));
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}