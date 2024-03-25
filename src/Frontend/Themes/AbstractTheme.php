<?php

namespace App\Frontend\Themes;

use JsonSerializable;

abstract class AbstractTheme implements JsonSerializable
{
    public function getName(): string
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $name = str_replace('Theme', '', $className);

        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
    }

    abstract public function getPrimaryColor(): string;

    abstract public function getSecondaryColor(): string;

    abstract public function getBackgroundColor(): string;

    abstract public function getErrorColor(): string;

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'primaryColor' => $this->getPrimaryColor(),
            'secondaryColor' => $this->getSecondaryColor(),
            'backgroundColor' => $this->getBackgroundColor(),
            'errorColor' => $this->getErrorColor(),
        ];
    }
}