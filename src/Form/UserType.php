<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Form\UserDetailsType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class UserType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options):void{
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'e.g. anapop3@gmail.com'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => '********'
                ]
            ])
            ->add('role', HiddenType::class, [
                'data' => 'ROLE_USER',
                'mapped' => true,
            ])
            ->add('token', HiddenType::class, [
                'data' => 'aaaaa',
                'mapped' => true,
            ])
            ->add('details',  UserDetailsType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-outline-secondary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver):void{
        $resolver->setDefaults(array(


        ));
    }

}
