<?php
namespace App\Form;

use App\Entity\User;
use App\Form\UserDetailsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class EditUserType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options):void{
        $builder
            ->add('email', EmailType::class, [])
            ->add('role', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'attr' => [
                    'class' => 'custom-input',
                ],
                'placeholder' => 'Select role',
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

      'data_class'=>User::class,
        ));
    }

}
