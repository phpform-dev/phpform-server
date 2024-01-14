<?php
namespace App\Admin\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'required' => !$options['is_edit'] ?? true,
                'mapped' => false,
            ]);

        if (!$options['is_current_user']) {
            $builder->add('is_superuser', CheckboxType::class, [
                'label' => 'Is Admin',
                'required' => false,
                'attr' => [
                    '@click' => 'isPermissionsOpen = !isPermissionsOpen',
                ]
            ]);
        }

        $builder->add('save', SubmitType::class, ['label' => 'Save']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
            'is_current_user' => false,
        ]);
    }
}