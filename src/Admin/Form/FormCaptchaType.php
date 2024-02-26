<?php
namespace App\Admin\Form;

use App\Captcha\Captcha;
use App\Captcha\CaptchaProviderInterface;
use App\Entity\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FormCaptchaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('captcha_provider', ChoiceType::class, [
                'label' => 'Captcha Provider',
                'choices' => array_flip(array_map(static function(CaptchaProviderInterface $provider){
                    return $provider->getName();
                }, (new Captcha())->getProviders())),
            ])
            ->add('captcha_token', null, ['label' => 'Secret Key'])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Form::class,
        ]);
    }
}