<?php
namespace App\Form;

use App\Entity\Stage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class ScheduleType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options):void{
        $builder
            ->add('stage', EntityType::class, [
                'class'=>Stage:: class,
                'choice_label'=>'name',
                'placeholder'=>'Select Stage'
            ])
            ->add('day', TextType::class, [
                'label' => 'Day',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'e.g. 1'
                ]
            ])
            ->add('date', DateType::class, [
                'label' => 'Date'

            ])

            ->add('startTime', TimeType::class, [
                'label' => 'Start Time'

            ])
            ->add('endTime', TimeType::class, [
                'label' => 'End Time'
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


        ));
    }

}
