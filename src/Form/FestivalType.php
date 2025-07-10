<?php
namespace App\Form;

use App\Entity\Festival;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class FestivalType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options):void{
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'e.g. Untold'
                ]
            ])
            ->add('location', TextType::class, [
                'label' => 'Location',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'e.g. Cluj'
                ]
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Start Date',
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => (new DateTime())->format('Y-m-d'),
                        'message' => 'Start date cannot be in the past.',
                    ])
                ]
//                ,
//                'attr' => [
//                    'min' => (new DateTime())->format('Y-m-d'),
//                ]

            ])
            ->add('endDate', DateType::class, [
                'label' => 'End Date',
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => (new DateTime())->format('Y-m-d'),
                        'message' => 'End date cannot be in the past.',
                    ])
                ]
//                ,
//                'attr' => [
//                    'min' => (new DateTime())->format('Y-m-d'),
//                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-outline-secondary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver):void{
        $resolver->setDefaults(array(

            'data_class' => Festival::class,
        ));
    }

}
