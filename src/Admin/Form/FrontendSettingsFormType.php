<?php

namespace App\Admin\Form;

use App\Entity\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FrontendSettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('frontend_is_enabled', CheckboxType::class, [
                'label' => 'Enable Static Frontend For this Form',
                'required' => false,
            ])
            ->add('frontend_type', ChoiceType::class, [
                'label' => 'Form Type',
                'choices' => [
                    'One Page Form' => 0,
                    'Stepper' => 1,
                ],
                'expanded' => true, // Renders as radio buttons
                'multiple' => false,
            ])
            ->add('frontend_captcha_site_key', TextType::class, [
                'label' => 'Captcha Site Key',
                'help' => 'If you activated any of captcha providers, you need to provide the site key here.',
                'required' => false,
            ])
            ->add('frontend_head_html', TextareaType::class, [
                'label' => 'Head HTML',
                'required' => false,
                'help' => 'This will be added to the head of the form page.',
            ])
            ->add('frontend_disclaimer', TextareaType::class, [
                'label' => 'Disclaimer',
                'required' => false,
                'help' => 'This will be added to the bottom of the form page.',
            ])
            ->add('frontend_confirmation_title', TextType::class, [
                'label' => 'Confirmation Title',
                'required' => false,
                'help' => 'This will be the title and header of the confirmation page.',
            ])
            ->add('frontend_confirmation', TextareaType::class, [
                'label' => 'Confirmation Text',
                'required' => false,
                'help' => 'This will be the content of the confirmation page.',
            ])
            ->add('frontend_confirmation_redirect', UrlType::class, [
                'label' => 'Confirmation Redirect',
                'required' => false,
                'help' => 'Put the URL here if you want to redirect the user to a different page after the form is submitted.',
            ])
            ->add('frontend_theme', HiddenType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Form::class,
        ]);
    }
}
