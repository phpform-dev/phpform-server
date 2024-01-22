<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\Index(columns: ['email'], name: 'users_email_idx')]
#[ORM\Index(columns: ['name'], name: 'users_name_idx')]
class User implements PasswordAuthenticatedUserInterface, UserInterface, JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30, unique: true)]
    #[Assert\Length(min: 1, max: 30)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private ?bool $is_superuser = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserPermission::class, orphanRemoval: true, cascade: ['all'], fetch: 'EAGER')]
    private Collection $permissions;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserBrowserToken::class, orphanRemoval: true)]
    private Collection $browserTokens;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: FormNotificationConfig::class, orphanRemoval: true)]
    private Collection $formNotificationConfigs;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
        $this->browserTokens = new ArrayCollection();
        $this->formNotificationConfigs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getIsSuperuser(): ?bool
    {
        return $this->is_superuser;
    }

    public function setIsSuperuser(bool $is_superuser): static
    {
        $this->is_superuser = $is_superuser;

        return $this;
    }

    /**
     * @return Collection<int, UserPermission>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(UserPermission $permission): static
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
            $permission->setUser($this);
        }

        return $this;
    }

    /**
     * @param Form[] $forms
     * @return $this
     */
    public function setPermissions(array $forms): static
    {
        foreach ($forms as $form) {
            $permission = new UserPermission();
            $permission->setForm($form);
            $this->permissions->add($permission);
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        if ($this->is_superuser) {
            $roles[] = 'ROLE_ADMIN';
        }

        return $roles;
    }

    public function eraseCredentials(): void
    {

    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'isSuperuser' => $this->getIsSuperuser(),
        ];
    }

    /**
     * @return Collection<int, UserBrowserToken>
     */
    public function getBrowserTokens(): Collection
    {
        return $this->browserTokens;
    }

    public function addBrowserToken(UserBrowserToken $browserToken): static
    {
        if (!$this->browserTokens->contains($browserToken)) {
            $this->browserTokens->add($browserToken);
            $browserToken->setUser($this);
        }

        return $this;
    }

    public function removeBrowserToken(UserBrowserToken $browserToken): static
    {
        if ($this->browserTokens->removeElement($browserToken)) {
            // set the owning side to null (unless already changed)
            if ($browserToken->getUser() === $this) {
                $browserToken->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FormNotificationConfig>
     */
    public function getFormNotificationConfigs(): Collection
    {
        return $this->formNotificationConfigs;
    }

    public function addFormNotificationConfig(FormNotificationConfig $formNotificationConfig): static
    {
        if (!$this->formNotificationConfigs->contains($formNotificationConfig)) {
            $this->formNotificationConfigs->add($formNotificationConfig);
            $formNotificationConfig->setUser($this);
        }

        return $this;
    }

    public function removeFormNotificationConfig(FormNotificationConfig $formNotificationConfig): static
    {
        if ($this->formNotificationConfigs->removeElement($formNotificationConfig)) {
            // set the owning side to null (unless already changed)
            if ($formNotificationConfig->getUser() === $this) {
                $formNotificationConfig->setUser(null);
            }
        }

        return $this;
    }
}
