<?php

namespace App\Entity;

use App\Repository\UserPermissionRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: UserPermissionRepository::class)]
#[ORM\Table(name: 'user_permissions')]
class UserPermission implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'permissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'userPermissions', targetEntity: Form::class, fetch: 'EAGER', cascade: ['all'])]
    private ?Form $form = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function setForm(Form $form): static
    {
        $this->form = $form;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'user' => $this->getUser()->jsonSerialize(),
            'form' => $this->getForm()->jsonSerialize(),
        ];
    }
}
