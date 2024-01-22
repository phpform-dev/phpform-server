<?php
namespace App\Admin\Controller;

use App\Admin\Dto\FormFieldRequestDto;
use App\Service\FormFieldService;
use App\Service\FormFieldTypeService;
use App\Service\FormMenuCounterService;
use App\Service\FormService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormFieldController extends AbstractController
{
    public function __construct(
        private readonly FormFieldTypeService $formFieldTypeService,
        private readonly FormFieldService $formFieldService,
        private readonly FormService $formService,
        private readonly FormMenuCounterService $formMenuCounterService,
    )
    {
    }

    #[Route('/admin/api/forms/{id}/fields', name: 'admin_api_forms_fields', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $formId = $request->attributes->get('id');
        $all = $this->formFieldService->getAllByFormId($formId);

        return $this->json([
            'data' => $all,
        ]);
    }

    #[Route('/admin/api/forms/{id}/fields', methods: ['POST'], format: 'json')]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $formId = $request->attributes->get('id');

        $data = json_decode($request->getContent(), true);
        $dto = new FormFieldRequestDto();

        $dto->name = $data['name'] ?? '';
        $dto->type = $data['type'] ?? '';
        $dto->label = $data['label'] ?? '';
        $dto->hint = $data['hint'] ?? null;
        $dto->isRequired = $data['isRequired'] ?? false;

        $errors = $validator->validate($dto);

        if (count($errors) === 0) {
            $availableTypes = $this->formFieldTypeService->getTypeNames();
            if (!in_array($dto->type, $availableTypes)) {
                $errors->add(new ConstraintViolation(
                    'Invalid type',
                    'Invalid type',
                    [],
                    $dto,
                    'type',
                    $dto->type
                ));
            }
        }

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = [
                    'property' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                    'code' => $error->getCode(),
                ];
            }

            return $this->json([
                'success' => false,
                'errors' => $errorMessages
            ], Response::HTTP_BAD_REQUEST);
        }

        $formFieldEntity = $this->formFieldService->create(
            $formId,
            $dto->type,
            $dto->name,
            $dto->label,
            $dto->hint,
            $dto->isRequired,
            $this->formFieldTypeService->getValidationParams($dto->type, $data['validations'] ?? [])
        );

        if ($formFieldEntity === null) {
            return $this->json([
                'success' => false,
                'errors' => [
                    [
                        'property' => 'name',
                        'message' => 'Name must be unique',
                        'code' => 'unique',
                    ]
                ]
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'data' => $formFieldEntity,
        ]);
    }

    #[Route('/admin/forms/{id}/fields/{fieldId}/delete', name: 'admin_forms_fields_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, int $id, int $fieldId): Response
    {
        $formEntity = $this->formService->getById($id);
        if ($formEntity === null) {
            throw $this->createNotFoundException();
        }

        $formFieldEntity = $this->formFieldService->getById($fieldId);
        if ($formFieldEntity === null) {
            throw $this->createNotFoundException();
        }

        if($request->isMethod('POST')) {
            $this->formFieldService->deleteById($fieldId);
            $this->addFlash('primary', 'Form field has been removed successfully');

            return $this->redirectToRoute('admin_forms_fields', ['id' => $formEntity->getId()]);
        }

        return $this->render('@Admin/form-fields/delete.html.twig', [
            'formEntity' => $formEntity,
            'formFieldEntity' => $formFieldEntity,
            'menuCounts' => $this->formMenuCounterService->getAllCountsByFormId($formEntity->getId()),
        ]);
    }

    #[Route('/admin/forms/{id}/fields/{fieldId}/edit', name: 'admin_forms_fields_edit', methods: ['GET', 'PUT'])]
    public function edit(Request $request, int $id, int $fieldId): Response
    {
        $formEntity = $this->formService->getById($id);
        if ($formEntity === null) {
            throw $this->createNotFoundException();
        }

        $formFieldEntity = $this->formFieldService->getById($fieldId);
        if ($formFieldEntity === null) {
            throw $this->createNotFoundException();
        }

        if($request->isMethod('PUT')) {
            $data = json_decode($request->getContent(), true);

            $this->formFieldService->edit(
                $fieldId,
                $data['type'],
                $data['name'],
                $data['label'],
                $data['hint'] ?? null,
                $data['isRequired'] ?? false,
                $this->formFieldTypeService->getValidationParams($data['type'], $data['validations'] ?? [])
            );

            return $this->json([
                'data' => $formFieldEntity,
            ]);
        }

        return $this->render('@Admin/form-fields/edit.html.twig', [
            'formEntity' => $formEntity,
            'formFieldEntity' => $formFieldEntity,
            'menuCounts' => $this->formMenuCounterService->getAllCountsByFormId($formEntity->getId()),
        ]);
    }

    #[Route('/admin/api/forms/{id}/fields/{fieldId}/move-down', methods: ['GET'], format: 'json')]
    public function moveDown(int $fieldId): JsonResponse
    {
        $this->formFieldService->moveDown($fieldId);

        return $this->json([]);
    }

    #[Route('/admin/api/forms/{id}/fields/{fieldId}/move-up', methods: ['GET'], format: 'json')]
    public function moveUp(int $fieldId): JsonResponse
    {
        $this->formFieldService->moveUp($fieldId);

        return $this->json([]);
    }
}