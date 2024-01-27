<?php

namespace App\Admin\Controller;

use App\Admin\Form\ConfigsType;
use App\Service\ConfigService;
use Minishlink\WebPush\VAPID;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfigController extends AbstractController
{
    public function __construct(
        private readonly ConfigService $configService,
    )
    {
    }

    #[Route('/admin/configs', name: 'admin_configs')]
    public function index(Request $request): Response
    {
        $configKeys = [
            $this->configService::VAPID_PUBLIC_KEY,
            $this->configService::VAPID_PRIVATE_KEY,
            $this->configService::SMPT_HOST,
            $this->configService::SMPT_PORT,
            $this->configService::SMPT_USERNAME,
            $this->configService::SMPT_PASSWORD,
        ];

        $form = $this->createForm(ConfigsType::class, $this->configService->getMany($configKeys));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($configKeys as $configKey) {
                $this->configService->set($configKey, $form->get($configKey)->getData() ?? '');
            }
    
            $this->addFlash('success', 'Configs updated!');
            return $this->redirectToRoute('admin_configs');
        }

        return $this->render('@Admin/configs/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/configs/generate-vapid-keys', name: 'admin_configs_generate_vapid_keys')]
    public function generateVapidKeys(Request $request): Response
    {
        if (!$this->configService->isBrowserPushNotificationsEnabled() || $request->isMethod('POST')) {
            $vapidKeys = VAPID::createVapidKeys();
            $this->configService->set($this->configService::VAPID_PUBLIC_KEY, $vapidKeys['publicKey']);
            $this->configService->set($this->configService::VAPID_PRIVATE_KEY, $vapidKeys['privateKey']);
            $this->addFlash('success', 'VAPID keys generated!');
            return $this->redirectToRoute('admin_configs');
        }

        return $this->render('@Admin/configs/generate_vapid_keys.html.twig');
    }
}