<?php

namespace App\Entity;

use App\Repository\FormFieldRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FormFieldRepository::class)]
#[ORM\Table(name: 'form_fields')]
#[ORM\Index(columns: ['form_id', 'position'], name: 'form_fields_form_position_idx')]
class FormField implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    private ?string $type = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 100)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $hint = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    #[Assert\NotNull]
    #[Assert\Type('bool')]
    private ?bool $is_required = null;

    #[ORM\Column]
    #[ORM\OrderBy(['position' => 'ASC'])]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private ?int $position = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $validations = [];

    #[ORM\ManyToOne(inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Form $form = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getValidations(): array
    {
        return $this->validations;
    }

    public function setValidations(array $validations): static
    {
        $this->validations = $validations;

        return $this;
    }

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function setForm(?Form $form): static
    {
        $this->form = $form;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getHint(): ?string
    {
        return $this->hint;
    }

    public function setHint(?string $hint): static
    {
        $this->hint = $hint;

        return $this;
    }

    public function getIsRequired(): ?bool
    {
        return $this->is_required;
    }

    public function setIsRequired(?bool $isRequired): static
    {
        $this->is_required = $isRequired;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'hint' => $this->getHint(),
            'position' => $this->getPosition(),
            'isRequired' => $this->getIsRequired(),
            'validations' => $this->getValidations(),
        ];
    }
}
