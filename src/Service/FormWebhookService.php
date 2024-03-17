<?php
namespace App\Service;

use App\Entity\Form;
use App\Entity\FormWebhook;
use App\Entity\Submission;
use App\Repository\FormWebhookRepository;
use GuzzleHttp\Client;

class FormWebhookService
{
    public function __construct(
        private readonly FormWebhookRepository $formWebhookRepository,
        private readonly FormFieldService $formFieldService,
    )
    {
    }

    public function getAllByFormId(int $formId): array
    {
        return $this->formWebhookRepository->findByFormId($formId);
    }

    public function getCountByFormId(int $formId): int
    {
        return $this->formWebhookRepository->countByFormId($formId);
    }

    public function save(FormWebhook $formWebhook): void
    {
        $this->formWebhookRepository->save($formWebhook);
    }

    public function getOneByIdAndFormId(int $webhookId, int $formId): ?FormWebhook
    {
        return $this->formWebhookRepository->findOneBy(['id' => $webhookId, 'form' => $formId]);
    }

    public function delete(FormWebhook $formWebhook): void
    {
        $this->formWebhookRepository->delete($formWebhook);
    }

    public function callAllWebhooks(Form $form, Submission $submission): void
    {
        $webhooks = $this->getAllByFormId($form->getId());
        $formFields = $this->formFieldService->getAllByFormId($form->getId());

        foreach ($webhooks as $webhook) {
            $payload = [
                'form' => [
                    'id' => $form->getId(),
                    'name' => $form->getName(),
                    'fields' => array_map(fn($formField) => [
                        'id' => $formField->getId(),
                        'name' => $formField->getName(),
                        'type' => $formField->getType(),
                        'label' => $formField->getLabel(),
                        'hint' => $formField->getHint(),
                        'is_required' => $formField->getIsRequired(),
                    ], $formFields),
                ],
                'data' => $submission,
            ];

            $headers = [];
            foreach($webhook->getHeaders() as $header) {
                $headers[$header->getName()] = $header->getValue();
            }
            $headers['Content-Type'] = 'application/json';

            $client = new Client();
            $client->request('POST', $webhook->getUrl(), [
                'json' => $payload,
                'headers' => $headers,
            ]);
        }
    }
}
