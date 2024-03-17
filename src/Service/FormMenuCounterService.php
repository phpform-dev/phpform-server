<?php
namespace App\Service;

class FormMenuCounterService {
    public function __construct(
        private readonly FormFieldService $formFieldService,
        private readonly FormSubmissionService $formSubmissionService,
        private readonly FormWebhookService $formWebhookService,
    )
    {
    }

    public function getAllCountsByFormId(int $formId): array
    {
        return [
            'fields' => $this->getFieldsCount($formId),
            'submissions' => [
                'active' => $this->getSubmissionCount($formId),
                'new' => $this->getNewSubmissionCount($formId),
                'flagged' => $this->getFlaggedSubmissionCount($formId),
                'deleted' => $this->getDeletedSubmissionCount($formId),
            ],
            'webhooks' => $this->formWebhookService->getCountByFormId($formId),
        ];
    }

    public function getFieldsCount(int $formId): int
    {
        return $this->formFieldService->getCountByFormId($formId);
    }

    public function getSubmissionCount(int $formId): int
    {
        return array_values($this->formSubmissionService->getCountAllByFormIds([$formId]))[0] ?? 0;
    }

    public function getNewSubmissionCount(int $formId): int
    {
        return array_values($this->formSubmissionService->getCountNewByFormIds([$formId]))[0] ?? 0;
    }

    public function getFlaggedSubmissionCount(int $formId): int
    {
        return array_values($this->formSubmissionService->getCountFlaggedByFormIds([$formId]))[0] ?? 0;
    }

    public function getDeletedSubmissionCount(int $formId): int
    {
        return array_values($this->formSubmissionService->getCountDeletedByFormIds([$formId]))[0] ?? 0;
    }
}