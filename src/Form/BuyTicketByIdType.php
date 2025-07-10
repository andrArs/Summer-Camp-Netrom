<?php
namespace App\Form;

use App\Entity\Artist;
use App\Entity\Festival;
use App\Entity\Schedule;
use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class BuyTicketByIdType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options):void{
        $builder
            ->add('festival', EntityType::class, [
                'class' => Festival::class,
                'choice_label' => 'name',
                'placeholder' => 'Select Festival',
                'query_builder' => function (EntityRepository $er) {
                    $today = new \DateTime();
                    return $er->createQueryBuilder('f')
                        ->where('f.EndDate >= :today')
                        ->setParameter('today', $today)
                        ->orderBy('f.start_date', 'ASC');
                },
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
