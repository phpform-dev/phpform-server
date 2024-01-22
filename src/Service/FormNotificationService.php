<?php
namespace App\Service;

use App\Entity\Form;
use App\Entity\User;
use ErrorException;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class FormNotificationService
{
    public function __construct(
        private readonly FormNotificationConfigService $formNotificationConfigService,
        private readonly UserBrowserTokenService $userBrowserTokenService,
        private readonly ConfigService $configService,
    )
    {
    }

    public function sendAllByFormId(int $formId): void
    {
        // TODO: Queue?
        $formNotificationConfigs = $this->formNotificationConfigService->getAllByFormId($formId);
        foreach ($formNotificationConfigs as $formNotificationConfig) {
            if ($formNotificationConfig->getIsBrowserPushEnabled()) {
                $user = $formNotificationConfig->getUser();
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
        }
    }

    /**
     * @throws ErrorException
     */
    public function sendBrowserPushNotification(User $user, Form $form, $subscription): void {
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
}
