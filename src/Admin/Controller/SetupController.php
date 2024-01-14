<?php
namespace App\Admin\Controller;

use App\Admin\Form\SetupType;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SetupController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    #[Route('/admin/setup', name: 'admin_setup')]
    public function setup(Request $request): Response
    {
        if ($this->userService->getCount() > 0) {
            return $this->redirectToRoute('admin_forms');
        }

        $user = new User();
        $user->setIsSuperuser(true);
        $form = $this->createForm(SetupType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $this->userService->create($user);

            return $this->redirectToRoute('admin_forms');
        }

        return $this->render('@Admin/setup.html.twig', [
            'adminForm' => $form->createView(),
        ]);
    }
}