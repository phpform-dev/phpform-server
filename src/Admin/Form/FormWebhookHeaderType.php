<?php
namespace App\Admin\Form;

use App\Entity\FormWebhookHeader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormWebhookHeaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Header Name',
                'attr' => ['placeholder' => 'Content-Type'],
            ])
            ->add('value', TextType::class, [
                'label' => 'Header Value',
                'attr' => ['placeholder' => 'application/json'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FormWebhookHeader::class,
        ]);
    }
}
