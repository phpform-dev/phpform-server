<?php
namespace App\Admin\Controller;

use App\Admin\Form\FormWebhookType;
use App\Entity\Form;
use App\Entity\FormWebhook;
use App\Service\FormMenuCounterService;
use App\Service\FormWebhookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormWebhooksController extends AbstractController
{
    public function __construct(
        private readonly FormMenuCounterService $formMenuCounterService,
        private readonly FormWebhookService $formWebhookService,
    )
    {
    }

    #[Route('/admin/forms/{id}/webhooks', name: 'admin_forms_webhooks', methods: ['GET', 'POST'])]
    public function index(Request $request, Form $formEntity): Response
    {
        $form = $this->createForm(FormWebhookType::class, new FormWebhook());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $webhook = $form->getData();
            $webhook->setForm($formEntity);
            $this->formWebhookService->save($webhook);

            $this->addFlash('primary', 'Webhook added successfully.');

            return $this->redirectToRoute('admin_forms_webhooks', ['id' => $formEntity->getId()]);
        }

        $webhooks = $this->formWebhookService->getAllByFormId($formEntity->getId());

        return $this->render('@Admin/form-webhooks/index.html.twig', [
            'formEntity' => $formEntity,
            'form' => $form->createView(),
            'menuCounts' => $this->formMenuCounterService->getAllCountsByFormId($formEntity->getId()),
            'webhooks' => $webhooks,
        ]);
    }

    #[Route('/admin/forms/{formId}/webhooks/{webhookId}/delete', name: 'admin_forms_webhook_delete', methods: ['GET'])]
    public function delete(int $formId, int $webhookId): Response
    {
        $webhook = $this->formWebhookService->getOneByIdAndFormId($webhookId, $formId);
        if (!$webhook) {
            throw $this->createNotFoundException('Webhook not found.');
        }

        $this->formWebhookService->delete($webhook);

        $this->addFlash('primary', 'Webhook deleted successfully.');

        return $this->redirectToRoute('admin_forms_webhooks', ['id' => $formId]);
    }
}