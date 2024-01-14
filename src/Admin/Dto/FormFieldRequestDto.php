<?php
namespace App\Admin\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class FormFieldRequestDto
{
    #[Assert\NotBlank]
    public string $type;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 50)]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]+$/i', message: 'Only letters, numbers and underscores are allowed.')]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 100)]
    public string $label;

    #[Assert\Length(max: 255)]
    public ?string $hint = null;

    public ?bool $isRequired = false;
}
