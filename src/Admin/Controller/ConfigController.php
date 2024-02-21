<?php

namespace App\Admin\Controller;

use App\Admin\Form\ConfigsType;
use App\Email\SubmissionEmail;
use App\Service\ConfigService;
use App\Service\EmailService;
use Minishlink\WebPush\VAPID;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfigController extends AbstractController
{
    public function __construct(
        private readonly ConfigService $configService,
        private readonly EmailService $emailService,
    )
    {
    }

    #[Route('/admin/configs', name: 'admin_configs')]
    public function index(Request $request): Response
    {
        $configKeys = [
            $this->configService::VAPID_PUBLIC_KEY,
            $this->configService::VAPID_PRIVATE_KEY,
            $this->configService::SMTP_HOST,
            $this->configService::SMTP_PORT,
            $this->configService::SMTP_USERNAME,
            $this->configService::SMTP_PASSWORD,
            $this->configService::SMTP_ENCRYPTION,
            $this->configService::SMTP_FROM_EMAIL,
            $this->configService::SMTP_FROM_NAME,
        ];

        $configs = $this->configService->getMany($configKeys);
        if (empty($configs[$this->configService::SMTP_ENCRYPTION])) {
            $configs[$this->configService::SMTP_ENCRYPTION] = 'ssl';
        }

        $form = $this->createForm(ConfigsType::class, $configs);
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

    #[Route('/admin/configs/test-smtp-connection', name: 'admin_configs_test_smtp_connection', methods: ['POST'], format: 'json')]
    public function testSmtpConnection(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $smtpHost = $data['host'] ?? null;
        $smtpPort = (int) ($data['port'] ?? 0);
        $smtpUsername = $data['username'] ?? null;
        $smtpPassword = $data['password'] ?? null;
        $smtpEncryption = $data['encryption'] ?? null;
        $smtpFromEmail = $data['from_email'] ?? null;
        $smtpFromName = $data['from_name'] ?? null;

        if (empty($smtpHost) || empty($smtpPort) || empty($smtpUsername) || empty($smtpPassword) || empty($smtpEncryption) || empty($smtpFromEmail) || empty($smtpFromName)) {
            throw new \InvalidArgumentException('Missing required data');
        }

        $dsn = $this->emailService->buildSmtpDsn($smtpHost, $smtpPort, $smtpUsername, $smtpPassword, $smtpEncryption);
        $email = SubmissionEmail::invoke(
            $smtpFromEmail, $smtpFromEmail, $smtpFromName, 'Test', 1);

        $success = $this->emailService->sendEmail($dsn, $email);

        return $this->json([
            'success' => $success,
            'error' => $success ? null : $this->emailService->getLastSendEmailError(),
        ]);
    }
}