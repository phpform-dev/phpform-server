<?php
namespace App\Admin\Controller;

use App\Service\FormMenuCounterService;
use App\Service\FormService;
use App\Service\FormSubmissionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

class FormSubmissionController extends AbstractController 
{
    const DEFAULT_PER_PAGE = 25;

    public function __construct(
        private readonly FormSubmissionService $formSubmissionService,
        private readonly FormService $formService,
        private readonly FormMenuCounterService $formMenuCounterService,
    )
    {
    }

    #[Route('/admin/form/{id}/submissions', name: 'admin_forms_submissions', methods: ['GET'])]
    public function list(int $id, int $page = 1, int $perPage = self::DEFAULT_PER_PAGE): Response
    {
        return $this->renderListFor($id, 'All', $page, $perPage);
    }

    #[Route('/admin/form/{id}/submissions/new', name: 'admin_forms_submissions_new', methods: ['GET'])]
    public function listNew(int $id, int $page = 1, int $perPage = self::DEFAULT_PER_PAGE): Response
    {
        return $this->renderListFor($id, 'New', $page, $perPage);
    }

    #[Route('/admin/form/{id}/submissions/flagged', name: 'admin_forms_submissions_flagged', methods: ['GET'])]
    public function listFlagged(int $id, int $page = 1, int $perPage = self::DEFAULT_PER_PAGE): Response
    {
        return $this->renderListFor($id, 'Flagged', $page, $perPage);
    }

    #[Route('/admin/form/{id}/submissions/deleted', name: 'admin_forms_submissions_deleted', methods: ['GET'])]
    public function listDeleted(int $id, int $page = 1, int $perPage = self::DEFAULT_PER_PAGE): Response
    {
        return $this->renderListFor($id, 'Deleted', $page, $perPage);
    }

    #[Route('/admin/form/{id}/submissions/{submissionId}/delete', name: 'admin_forms_submissions_delete', methods: ['GET'])]
    public function delete(Request $request, int $id, int $submissionId): Response
    {
        $this->formSubmissionService->delete($submissionId);

        return $this->redirectBack($request);
    }

    #[Route('/admin/form/{id}/submissions/{submissionId}/flag', name: 'admin_forms_submissions_flag', methods: ['GET'])]
    public function flag(Request $request, int $id, int $submissionId): Response
    {
        $this->formSubmissionService->flag($submissionId);

        return $this->redirectBack($request);
    }

    #[Route('/admin/form/{id}/submissions/{submissionId}/unflag', name: 'admin_forms_submissions_unflag', methods: ['GET'])]
    public function unflag(Request $request, int $id, int $submissionId): Response
    {
        $this->formSubmissionService->unflag($submissionId);

        return $this->redirectBack($request);
    }

    #[Route('/admin/form/{id}/submissions/{submissionId}/read', name: 'admin_forms_submissions_read', methods: ['GET'])]
    public function read(Request $request, int $id, int $submissionId): Response
    {
        $this->formSubmissionService->read($submissionId);

        return $this->redirectBack($request);
    }

    #[Route('/admin/form/{id}/submissions/{submissionId}/unread', name: 'admin_forms_submissions_unread', methods: ['GET'])]
    public function unread(Request $request, int $id, int $submissionId): Response
    {
        $this->formSubmissionService->unread($submissionId);

        return $this->redirectBack($request);
    }

    #[Route('/admin/form/{id}/submissions/{submissionId}/undelete', name: 'admin_forms_submissions_undelete', methods: ['GET'])]
    public function undelete(Request $request, int $id, int $submissionId): Response
    {
        $this->formSubmissionService->undelete($submissionId);

        return $this->redirectBack($request);
    }

    #[Route('/admin/form/{id}/submissions/export/{status}/{type}', name: 'admin_forms_submissions_export', methods: ['GET'])]
    public function export(int $id, string $status, string $type): Response
    {
        if (!in_array($status, ['all', 'new', 'flagged', 'deleted'])) {
            throw new \InvalidArgumentException('Invalid type');
        }

        if (!in_array($type, ['csv', 'xls'])) {
            throw new \InvalidArgumentException('Invalid type');
        }

        ob_start();

        // Create the Response Header
        $response = new Response();
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'export.' . $status . '.' . $type
        );
        $response->headers->set('Content-Disposition', $disposition);

        $writer = $this->formSubmissionService->export($id, $status, $type);

        if ($type === 'csv') {
            $writer->setDelimiter(',');
            $writer->setEnclosure('"');
            $writer->setLineEnding("\r\n");
            $writer->setSheetIndex(0);
        }
        $writer->save('php://output');

        return $response;
    }

    private function renderListFor(int $formId, string $type, int $page = 1, int $perPage = 50): Response
    {
        if (!in_array($type, ['All', 'New', 'Flagged', 'Deleted'])) {
            throw new \InvalidArgumentException('Invalid type');
        }

        $submissions = $this->formSubmissionService->{'get' . $type . 'ByFormId'}($formId, $page, $perPage);
        $total = array_values($this->formSubmissionService->{'getCount' . $type . 'ByFormIds'}([$formId]))[0] ?? 0;

        return $this->render('@Admin/form-submission/index.html.twig', [
            'formEntity' => $this->formService->getById($formId),
            'submissions' => array_map(fn($submission) => $submission->jsonSerialize(), $submissions),
            'total' => $total,
            'menuCounts' => $this->formMenuCounterService->getAllCountsByFormId($formId),
            'status' => $type,
        ]);
    }

    private function redirectBack(Request $request): Response
    {
        return $this->redirect($request->headers->get('referer'));
    }
}