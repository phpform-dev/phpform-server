<?php

namespace App\Entity;

use App\Repository\FormNotificationConfigRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormNotificationConfigRepository::class)]
#[ORM\Table(name: 'form_notification_configs')]
class FormNotificationConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Form $form = null;

    #[ORM\ManyToOne(inversedBy: 'formNotificationConfigs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $is_browser_push_enabled = null;

    #[ORM\Column]
    private ?bool $is_email_enabled = null;

    private $last_notification_sent_at = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getIsBrowserPushEnabled(): ?bool
    {
        return $this->is_browser_push_enabled;
    }

    public function setIsBrowserPushEnabled(bool $is_browser_push_enabled): static
    {
        $this->is_browser_push_enabled = $is_browser_push_enabled;

        return $this;
    }

    public function getIsEmailEnabled(): ?bool
    {
        return $this->is_email_enabled;
    }

    public function setIsEmailEnabled(bool $is_email_enabled): static
    {
        $this->is_email_enabled = $is_email_enabled;

        return $this;
    }

    public function getLastNotificationSentAt(): ?\DateTimeImmutable
    {
        return $this->last_notification_sent_at;
    }

    public function setLastNotificationSentAt(\DateTimeImmutable $last_notification_sent_at): static
    {
        $this->last_notification_sent_at = $last_notification_sent_at;

        return $this;
    }
}
