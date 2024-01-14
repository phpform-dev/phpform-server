<?php

namespace App\Admin\Controller;

use App\Admin\Form\UserFormType;
use App\Entity\User;
use App\Service\FormService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

    public function __construct(
        private readonly UserService $userService,
        private readonly FormService $formService,
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function index(): Response
    {
        $users = $this->userService->getAll();
        return $this->render('@Admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/create', name: 'admin_users_create')]
    public function create(Request $request, UserService $userService): Response
    {
        $userEntity = new User();
        $form = $this->createForm(UserFormType::class, $userEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEntity->setPassword($this->passwordHasher->hashPassword($userEntity, $form->get('password')->getData()));
            $permissions = $request->get('permissions');
            $userService->create($userEntity);
            $this->userService->addAccessToForms($userEntity, $permissions ?? []);

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('@Admin/users/create.html.twig', [
            'form' => $form->createView(),
            'forms' => $this->formService->getAll(),
        ]);
    }

    #[Route('/admin/users/delete/{id}', name: 'admin_users_delete')]
    public function delete(Request $request, User $user): Response
    {
        if ($request->isMethod('POST')) {
            $this->userService->deleteById($user->getId());
            $this->addFlash('success', 'User deleted successfully');
            return $this->redirectToRoute('admin_users');
        }

        $user = $this->userService->getById($user->getId());
        return $this->render('@Admin/users/delete.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/admin/users/{id}/edit', name: 'admin_users_edit')]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserFormType::class, $user, [
            'is_edit' => true,
            'is_current_user' => $user->getId() === $this->getUser()->getId(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->get('password')->isEmpty()) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $form->get('password')->getData()));
            }

            $permissions = $request->get('permissions');
            $this->userService->edit($user);

            if ($user->getId() !== $this->getUser()->getId()) {
                $this->userService->replaceAccessToForms($user, $permissions ?? []);
            }

            $this->addFlash('primary', 'User updated successfully');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('@Admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'forms' => $this->formService->getAll(),
        ]);
    }

}
