<?php
namespace App\Admin\Controller;

use App\Service\FormFieldTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormFieldTypeController extends AbstractController
{
    public function __construct(private readonly FormFieldTypeService $service)
    {
    }

    #[Route('/admin/api/forms/fields/types', name: 'admin_api_forms_fields_types')]
    public function list(): Response
    {
        return $this->json([
            'data' => [
                'types' => $this->service->getAll(),
            ]
        ]);
    }
}