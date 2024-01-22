<?php

namespace App\Admin\Form;

use App\Service\ConfigService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigsType extends AbstractType
{
    public function __construct(
        private readonly ConfigService $configService,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($this->configService::VAPID_PUBLIC_KEY, TextType::class, ['required' => false])
            ->add($this->configService::VAPID_PRIVATE_KEY, TextType::class, ['required' => false])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }
}
