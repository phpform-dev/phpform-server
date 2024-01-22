<?php

namespace App\Entity;

use App\Repository\SubmissionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: SubmissionRepository::class)]
#[ORM\Table(name: 'submissions')]
#[ORM\Index(columns: ['form_id', 'created_at'], name: 'submissions_form_id_idx')]
class Submission implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $answers = [];

    #[ORM\Column]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $read_at = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $flagged_at = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $deleted_at = null;

    #[ORM\ManyToOne(inversedBy: 'submissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Form $form = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function setAnswers(array $answers): static
    {
        $this->answers = $answers;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

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

    public function jsonSerialize(): array
    {
        $answers = [];
        if (count($this->getAnswers()) > 0) {
            foreach ($this->getAnswers() as $key => $value) {
                $answers[] = [
                    'field' => $key,
                    'answer' => $value,
                ];
            }
        }

        return [
            'id' => $this->getId(),
            'answers' => $answers,
            'isRead' => $this->isRead(),
            'isFlagged' => $this->isFlagged(),
            'isDeleted' => $this->isDeleted(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    public function getReadAt(): ?DateTimeImmutable
    {
        return $this->read_at;
    }

    public function setReadAt(?DateTimeImmutable $read_at): static
    {
        $this->read_at = $read_at;

        return $this;
    }

    public function getFlaggedAt(): ?DateTimeImmutable
    {
        return $this->flagged_at;
    }

    public function setFlaggedAt(?DateTimeImmutable $flagged_at): static
    {
        $this->flagged_at = $flagged_at;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?DateTimeImmutable $deleted_at): static
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    public function isRead(): bool
    {
        return $this->getReadAt() !== null;
    }

    public function isFlagged(): bool
    {
        return $this->getFlaggedAt() !== null;
    }

    public function isDeleted(): bool
    {
        return $this->getDeletedAt() !== null;
    }
}
