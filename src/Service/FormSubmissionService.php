<?php
namespace App\Service;

use App\Event\NewSubmissionEvent;
use App\Repository\SubmissionRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class FormSubmissionService
{
    public function __construct(
        private FormFieldService $formFieldService,
        private FormFieldTypeService $formFieldTypeService,
        private SubmissionRepository $submissionRepository,
        private EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    public function submit(int $formId, array $data): ?array
    {
        $formFields = $this->formFieldService->getAllByFormId($formId);
        $errors = [];

        // Compare fields in the request and for
        // and if there are some extra fields in the request remove them
        $formFieldNames = array_map(fn($formField) => $formField->getName(), $formFields);
        $data = array_filter($data, fn($key) => in_array($key, $formFieldNames), ARRAY_FILTER_USE_KEY);


        foreach ($formFields as $formField) {
            $fieldType = $this->formFieldTypeService->getOneByType($formField->getType(), $formField->getValidations() ?? []);
            if (!isset($data[$formField->getName()])) {
                if ($formField->getIsRequired()) {
                    $errors[] = [
                        'field' => $formField->getName(),
                        'errors' => ['Field is required'],
                    ];
                }
                continue;
            }
            if (!$fieldType->validate($data[$formField->getName()] ?? null)) {
                $errors[] = [
                    'field' => $formField->getName(),
                    'errors' => $fieldType->getValidationErrors(),
                ];
            }
        }

        if (count($errors) === 0) {
            $submission = $this->submissionRepository->create($formId, $data);
            $this->eventDispatcher->dispatch(new NewSubmissionEvent($submission), 'submission.new');
        }

        return [
            'success' => count($errors) === 0,
            'errors' => $errors,
            'data' => !empty($submission) ? $submission : null,
        ];
    }

    public function getAllByFormId(int $formId, int $page = 1, int $perPage = 10): array
    {
        return $this->submissionRepository->findByFormIdWithPagination($formId, $page, $perPage);
    }

    public function getNewByFormId(int $formId, int $page = 1, int $perPage = 10): array
    {
        return $this->submissionRepository->findNewByFormIdWithPagination($formId, $page, $perPage);
    }

    public function getFlaggedByFormId(int $formId, int $page = 1, int $perPage = 10): array
    {
        return $this->submissionRepository->findFlaggedByFormIdWithPagination($formId, $page, $perPage);
    }

    public function getDeletedByFormId(int $formId, int $page = 1, int $perPage = 10): array
    {
        return $this->submissionRepository->findDeletedByFormIdWithPagination($formId, $page, $perPage);
    }

    public function getCountAllByFormIds(array $formIds): array
    {
        return $this->submissionRepository->getCountByStatus('all', $formIds);
    }

    public function getCountDeletedByFormIds(array $formIds): array
    {
        return $this->submissionRepository->getCountByStatus(SubmissionRepository::SUBMISSION_STATUS_DELETED, $formIds);
    }

    public function getCountFlaggedByFormIds(array $formIds): array
    {
        return $this->submissionRepository->getCountByStatus(SubmissionRepository::SUBMISSION_STATUS_FLAGGED, $formIds);
    }

    public function getCountNewByFormIds(array $formIds): array
    {
        return $this->submissionRepository->getCountByStatus(SubmissionRepository::SUBMISSION_STATUS_NEW, $formIds);
    }

    public function delete(int $id): void
    {
        $this->submissionRepository->delete($id);
    }

    public function flag(int $id): void
    {
        $this->submissionRepository->flag($id);
    }

    public function unflag(int $id): void
    {
        $this->submissionRepository->unflag($id);
    }

    public function read(int $id): void
    {
        $this->submissionRepository->read($id);
    }

    public function unread(int $id): void
    {
        $this->submissionRepository->unread($id);
    }

    public function undelete(int $id): void
    {
        $this->submissionRepository->undelete($id);
    }

    public function export(int $id, string $status, string $type): IWriter
    {
        $submissions = $this->{'get' . ucfirst($status) . 'ByFormId'}($id, 1, 1000000);

        $additionalHeaders = [];

        foreach ($submissions as $submission) {
            foreach ($submission['answers'] as $answer) {
                if (isset($additionalHeaders[$answer['field']])) {
                    continue;
                }
                $additionalHeaders[$answer['field']] = ucfirst($answer['field']);
            }
        }

        $rows[] = [
            'id' => 'ID',
            'createdAt' => 'Created At',
            'isRead' => 'Read',
            'isFlagged' => 'Flagged',
            'isDeleted' => 'Deleted',
            ...array_values($additionalHeaders)
        ];

        foreach ($submissions as $submission) {

            $rowAnswers = [];
            foreach ($additionalHeaders as $key => $header) {
                $answer = array_filter($submission['answers'], fn($answer) => $answer['field'] === $key);
                if (count($answer) === 0) {
                    $rowAnswers[] = '';
                    continue;
                }
                $answer = array_values($answer)[0]['answer'];
                if (is_array($answer)) {
                    $answer = implode(', ', $answer);
                }
                $rowAnswers[] = $answer;
            }

            $row = [
                $submission['id'],
                $submission['createdAt'],
                $submission['isRead'] ? 'Yes' : 'No',
                $submission['isFlagged'] ? 'Yes' : 'No',
                $submission['isDeleted'] ? 'Yes' : 'No',
                ...$rowAnswers,
            ];

            $rows[] = $row;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($rows, null, 'A1');

        return IOFactory::createWriter($spreadsheet, ucfirst($type));
    }
}