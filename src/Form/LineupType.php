<?php
namespace App\Form;

use App\Entity\Artist;
use App\Entity\Festival;
use App\Entity\Schedule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class LineupType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options):void{
        $builder
            ->add('festival', EntityType::class, [
                'class'=>Festival:: class,
                'choice_label'=>'name',
                'placeholder'=>'Select Festival'
            ])
            ->add('artist', EntityType::class, [
                'class'=>Artist::class,
                'choice_label'=>'name',
                'placeholder'=>'Select Artist'
            ])
            ->add('schedule', EntityType::class, [
                'class' => Schedule::class,
                'choice_label' => function($schedule) {
                    return sprintf(
                        '%s (%s - %s)',
                        $schedule->getDate()->format('Y-m-d'),
                        $schedule->getStartTime()->format('H:i'),
                        $schedule->getEndTime()->format('H:i')
                    );
                    },
                'placeholder' => 'Select Schedule',
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
