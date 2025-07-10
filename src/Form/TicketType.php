<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class TicketType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options):void{
        $builder
            ->add('type', TextType::class, [
                'label' => 'Type',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'e.g. Vip'
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'custom-input',

                ]
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Price',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => '230',
                    'min'=>1
                ],
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value'=>1,
                    ])
                ]
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
