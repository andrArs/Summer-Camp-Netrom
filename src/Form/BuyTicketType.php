<?php
namespace App\Form;

use App\Entity\Artist;
use App\Entity\Festival;
use App\Entity\Schedule;
use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class BuyTicketType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options):void{
        $builder
            ->add('ticket', EntityType::class, [
                'class'=>Ticket::class,
                'choice_label'=>'type',
                'placeholder'=>'Select Ticket'
            ])
            ->add('festival', EntityType::class, [
                'class'=>Festival::class,
                'choice_label'=>'name',
                'placeholder'=>'Select Festival'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Buy',
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
