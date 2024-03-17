<?php

namespace App\Entity;

use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

class FormWebhookHeader implements JsonSerializable
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private string $value;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}