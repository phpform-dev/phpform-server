<?php

namespace App\Entity;

use App\Repository\FormWebhookRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FormWebhookRepository::class)]
#[ORM\Table(name: 'form_webhooks')]
#[ORM\Index(columns: ['form_id'], name: 'form_webhooks_form_id_idx')]
class FormWebhook implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'webhooks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Form $form = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $url = null;

    /**
     * @var FormWebhookHeader[]|null $headers
     */
    #[ORM\Column(type: 'json', nullable: true)]
    #[Assert\All(constraints: [
        new Assert\Collection(
            fields: [
                'name' => [new Assert\NotBlank(), new Assert\Length(min: 1, max: 255)],
                'value' => [new Assert\NotBlank(), new Assert\Length(min: 1, max: 255)],
            ]
        )
    ])]
    private ?array $headers = null;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return FormWebhookHeader[]|null
     */
    public function getHeaders(): ?array
    {
        return array_map(fn(array $header) => (new FormWebhookHeader())->setName($header['name'])->setValue($header['value']), $this->headers ?? []);
    }

    /**
     * @param FormWebhookHeader[]|null $headers
     */

    public function setHeaders(?array $headers): static
    {
        $this->headers = array_map(fn(FormWebhookHeader $header) => $header->jsonSerialize(), $headers);

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'headers' => array_map(fn(FormWebhookHeader $header) => $header->jsonSerialize(), $this->headers ?? []),
        ];
    }
}