<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class ArtistType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options):void{
        $builder->add('name',TextType::class);
        $builder->add('genre',TextType::class);
        $builder->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver):void{
        $resolver->setDefaults(array(
//            'data_class' => 'App\Entity\Artist'

        ));
    }

}
