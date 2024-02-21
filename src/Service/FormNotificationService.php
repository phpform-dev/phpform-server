<?php
namespace App\Service;

use App\Email\SubmissionEmail;
use App\Entity\Form;
use App\Entity\User;
use ErrorException;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

readonly class FormNotificationService
{
    public function __construct(
        private FormNotificationConfigService $formNotificationConfigService,
        private UserBrowserTokenService       $userBrowserTokenService,
        private ConfigService                 $configService,
        private EmailService                  $emailService,
        private FormSubmissionService         $formSubmissionService,
    )
    {
    }

    public function sendAllByFormId(int $formId): void
    {
        // TODO: Queue?
        $formNotificationConfigs = $this->formNotificationConfigService->getAllByFormId($formId);
        foreach ($formNotificationConfigs as $formNotificationConfig) {
            $user = $formNotificationConfig->getUser();
            if ($formNotificationConfig->getIsBrowserPushEnabled()) {
                $userBrowserTokens = $this->userBrowserTokenService->getAllByUserId($user->getId());
                foreach ($userBrowserTokens as $userBrowserToken) {
                    try {
                        $this->sendBrowserPushNotification(
                            $user,
                            $formNotificationConfig->getForm(),
                            new Subscription(
                                $userBrowserToken->getEndpoint(),
                                $userBrowserToken->getPublicKey(),
                                $userBrowserToken->getAuthToken(),
                                'aesgcm',
                            ),
                        );
                    } catch (ErrorException $e) {

                    }
                }
            }

            if ($formNotificationConfig->getIsEmailEnabled()) {
                $this->sendEmailNotification($user, $formNotificationConfig->getForm());
            }
        }
    }

    /**
     * @throws ErrorException
     */
    public function sendBrowserPushNotification(User $user, Form $form, Subscription $subscription): void
    {
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:' . $user->getEmail(),
                'publicKey' => $this->configService->get($this->configService::VAPID_PUBLIC_KEY),
                'privateKey' => $this->configService->get($this->configService::VAPID_PRIVATE_KEY),
            ],
        ];

        $pushBody = [
            'title' => $form->getName(),
            'body' => 'Has one or more new submissions.',
        ];

        $webPush = new WebPush($auth);
        $webPush->queueNotification(
            $subscription,
            json_encode($pushBody)
        );

        $results = $webPush->flush();

        foreach ($results as $result) {
            if ($result->getResponse()->getStatusCode() === 410) {
                $this->userBrowserTokenService->deleteByEndpoint($result->getEndpoint());
            }
        }
    }

    public function sendEmailNotification(User $user, Form $form): void
    {
        $dsn = $this->emailService->buildSmtpDsnFromConfig();
        $email = SubmissionEmail::invoke(
            $user->getEmail(),
            $this->configService->get(ConfigService::SMTP_FROM_EMAIL),
            $this->configService->get(ConfigService::SMTP_FROM_NAME),
            $form->getName(),
            $this->formSubmissionService->getCountNewByFormIds([$form->getId()])[$form->getId()],
        );

        $this->emailService->sendEmail($dsn, $email);
    }
}
