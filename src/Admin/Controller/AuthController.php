<?php
namespace App\Admin\Controller;

use App\Admin\Form\LoginFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    #[Route('/admin/login', name: 'admin_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_forms');
        }

        if ($this->userService->getCount() === 0) {
            return $this->redirectToRoute('admin_setup');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginFormType::class, [
            'email' => $lastUsername,
        ]);

        return $this->render('@Admin/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    #[Route('/admin/logout', name: 'admin_logout')]
    public function logout()
    {
        // The route is handled by Symfony security
    }
}