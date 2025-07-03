<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class UserDetailsType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options):void{
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'e.g. Ana Pop'
                ]
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Age',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => '23'
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver):void{
        $resolver->setDefaults(array(

            'data_class' => \App\Entity\UserDetails::class,
        ));
    }

}
