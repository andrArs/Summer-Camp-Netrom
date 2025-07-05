<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class ArtistType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options):void{
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'e.g. The Motans'
                ]
            ])
            ->add('genre', TextType::class, [
                'label' => 'Genre',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'e.g. Pop'
                ]
            ])
            ->add('image', TextType::class, [
                'label' => 'Image',
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'e.g. https://assets.mycast.io/actor_images/actor-smiley-371577_large.jpg?1645509719'
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
//            'data_class' => 'App\Entity\Artist'

        ));
    }

}
