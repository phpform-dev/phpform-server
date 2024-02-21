<?php

namespace App\Admin\Form;

use App\Service\ConfigService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Hostname;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class ConfigsType extends AbstractType
{
    public function __construct(
        private readonly ConfigService $configService,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $smtpEncryptionChoices = [
            'SSL' => 'ssl',
            'TLS' => 'tls',
        ];

        $builder
            ->add($this->configService::VAPID_PUBLIC_KEY, TextType::class, [
                'required' => false,
                'label' => 'VAPID public key'
            ])
            ->add($this->configService::VAPID_PRIVATE_KEY, TextType::class, [
                'required' => false,
                'label' => 'VAPID private key'
            ])
            ->add($this->configService::SMTP_HOST, TextType::class, [
                'required' => false,
                'label' => 'Host',
                'constraints' => [
                    new Hostname()
                ],
            ])
            ->add($this->configService::SMTP_PORT, IntegerType::class, [
                'required' => false,
                'label' => 'Port',
                'constraints' => [
                    new Range(['min' => 1, 'max' => 65535])
                ],
            ])
            ->add($this->configService::SMTP_USERNAME, TextType::class, [
                'required' => false,
                'label' => 'Username',
            ])
            ->add($this->configService::SMTP_PASSWORD, TextType::class, [
                'required' => false,
                'label' => 'Password',
            ])
            ->add($this->configService::SMTP_FROM_EMAIL, TextType::class, [
                'required' => false,
                'label' => 'From email',
                'constraints' => [
                    new Email(),
                ],
            ])
            ->add($this->configService::SMTP_FROM_NAME, TextType::class, [
                'required' => false,
                'label' => 'From name',
            ])
            ->add($this->configService::SMTP_ENCRYPTION, ChoiceType::class, [
                'required' => true,
                'choices' => $smtpEncryptionChoices,
                'label' => 'Encryption',
                'constraints' => [
                    new NotBlank(),
                    new Choice(['choices' => array_values($smtpEncryptionChoices)])
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }
}
