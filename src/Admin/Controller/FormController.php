<?php
namespace App\Admin\Controller;

use App\Admin\Form\FormRecaptchaType;
use App\Admin\Form\FormSecretType;
use App\Entity\Form;
use App\Admin\Form\FormType;
use App\Service\FormMenuCounterService;
use App\Service\FormService;
use App\Service\FormSubmissionService;
use App\Service\TokenProtectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    public function __construct(
        private readonly FormService $formService,
        private readonly FormMenuCounterService $formMenuCounterService,
        private readonly FormSubmissionService $formSubmissionService,
    )
    {
    }

    #[Route('/admin', name: 'admin_forms')]
    public function list(): Response
    {
        return $this->render('@Admin/forms/index.html.twig', $this->getListData());
    }

    #[Route('/admin/forms/archived', name: 'admin_forms_archived')]
    public function listArchived(): Response
    {
        return $this->render('@Admin/forms/index.html.twig', $this->getListData(isArchived: true));
    }

    #[Route('/admin/forms/create', name: 'admin_forms_create')]
    public function create(Request $request): Response
    {
        $formEntity = new Form();
        $form = $this->createForm(FormType::class, $formEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formService->create($formEntity);

            return $this->redirectToRoute('admin_forms');
        }

        return $this->render('@Admin/forms/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/forms/{id}/edit', name: 'admin_forms_edit')]
    public function edit(Request $request, Form $formEntity): Response
    {
        $form = $this->createForm(FormType::class, $formEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formService->edit($formEntity);

            $this->addFlash('primary', 'Form updated successfully');

            return $this->redirectToRoute('admin_forms_edit', ['id' => $formEntity->getId()]);
        }

        return $this->render('@Admin/forms/edit.html.twig', [
            'form' => $form->createView(),
            'formEntity' => $formEntity,
            'menuCounts' => $this->formMenuCounterService->getAllCountsByFormId($formEntity->getId()),
        ]);
    }

    #[Route('/admin/forms/{id}/recaptcha', name: 'admin_forms_recaptcha')]
    public function recaptcha(Request $request, Form $formEntity): Response
    {
        $form = $this->createForm(FormRecaptchaType::class, $formEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formService->edit($formEntity);

            $this->addFlash('primary', 'Recaptcha Secret Key updated successfully');

            return $this->redirectToRoute('admin_forms_recaptcha', ['id' => $formEntity->getId()]);
        }

        return $this->render('@Admin/forms/recaptcha.html.twig', [
            'form' => $form->createView(),
            'formEntity' => $formEntity,
            'menuCounts' => $this->formMenuCounterService->getAllCountsByFormId($formEntity->getId()),
        ]);
    }

    #[Route('/admin/forms/{id}/token', name: 'admin_forms_token')]
    public function token(Request $request, Form $formEntity): Response
    {
        $form = $this->createForm(FormSecretType::class, $formEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formService->edit($formEntity);

            $this->addFlash('primary', 'Secret Key updated successfully');

            return $this->redirectToRoute('admin_forms_token', ['id' => $formEntity->getId()]);
        }

        return $this->render('@Admin/forms/token.html.twig', [
            'form' => $form->createView(),
            'formEntity' => $formEntity,
            'menuCounts' => $this->formMenuCounterService->getAllCountsByFormId($formEntity->getId()),
        ]);
    }

    #[Route('/admin/forms/{id}/fields', name: 'admin_forms_fields', methods: ['GET'])]
    public function fields(Request $request, Form $formEntity): Response
    {
        return $this->render('@Admin/forms/fields.html.twig', [
            'formEntity' => $formEntity,
            'menuCounts' => $this->formMenuCounterService->getAllCountsByFormId($formEntity->getId()),
        ]);
    }

    #[Route('/admin/forms/{id}/archive', name: 'admin_forms_archive')]
    public function archive(Request $request, Form $formEntity): Response
    {
        if ($formEntity->getDeletedAt() !== null) {
            $this->addFlash('danger', 'Form already archived');

            return $this->redirectToRoute('admin_forms');
        }

        if($request->isMethod('POST')) {
            $this->formService->archive($formEntity);
            $this->addFlash('primary', 'Form archived successfully');

            return $this->redirectToRoute('admin_forms_recover', ['id' => $formEntity->getId()]);
        }

        return $this->render('@Admin/forms/archive.html.twig', [
            'formEntity' => $formEntity,
            'menuCounts' => $this->formMenuCounterService->getAllCountsByFormId($formEntity->getId()),
        ]);
    }

    #[Route('/admin/forms/{id}/recover', name: 'admin_forms_recover')]
    public function recover(Request $request, Form $formEntity): Response
    {
        if($request->isMethod('POST')) {
            $this->formService->recover($formEntity);
            $this->addFlash('primary', 'Form recover successfully');

            return $this->redirectToRoute('admin_forms_archive', ['id' => $formEntity->getId()]);
        }

        return $this->render('@Admin/forms/recover.html.twig', [
            'formEntity' => $formEntity,
            'menuCounts' => $this->formMenuCounterService->getAllCountsByFormId($formEntity->getId()),
        ]);
    }

    #[Route('/admin/forms/{id}', name: 'admin_forms_info')]
    public function api(Form $formEntity): Response
    {
        $apiToken = null;
        if($formEntity->isTokenEnabled()) {
            $apiToken = (new TokenProtectionService($formEntity))->createToken('test');
        }

        return $this->render('@Admin/forms/api.html.twig', [
            'formEntity' => $formEntity,
            'menuCounts' => $this->formMenuCounterService->getAllCountsByFormId($formEntity->getId()),
            'apiToken' => $apiToken,
        ]);
    }

    private function getListData(bool $isArchived = false): array
    {
        $counts = $this->formService->getCountsForUser($this->getUser());

        $allowedFormIds = null;
        if (!$this->getUser()->getIsSuperuser()) {
            $allowedFormIds = $this->getUser()->getPermissions()->map(fn($p) => $p->getForm()->getId());
        }

        $forms = $this->formService->find(ids: $allowedFormIds, archived: $isArchived);
        $formIds = array_map(fn($f) => $f->getId(), $forms);

        // Getting counters for each form
        $activeSubmissions = $this->formSubmissionService->getCountAllByFormIds($formIds);
        $newSubmissions = $this->formSubmissionService->getCountNewByFormIds($formIds);
        $flaggedSubmissions = $this->formSubmissionService->getCountFlaggedByFormIds($formIds);

        $forms = array_map(function($form) use ($activeSubmissions, $newSubmissions, $flaggedSubmissions) {
            $form = $form->jsonSerialize();
            $form['activeSubmissions'] = $activeSubmissions[$form['id']] ?? 0;
            $form['newSubmissions'] = $newSubmissions[$form['id']] ?? 0;
            $form['flaggedSubmissions'] = $flaggedSubmissions[$form['id']] ?? 0;
            return $form;
        }, $forms);

        return [
            'forms' => $forms,
            'counts' => $counts,
        ];
    }
}