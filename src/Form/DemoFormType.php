<?php

namespace App\Form;

use App\Entity\Demo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label"=>"Nombre",
                "attr"=> [
                    "placeholder"=>"Vicent Chase"
                ]
                
            ])
            ->add('email', TextType::class, [
                "label"=>"Email",
                "attr"=> [
                    "placeholder"=>"vicent@example.com"
                ]
            ])
            ->add('city', ChoiceType::class, [
                "choices"=>[
                    "Madrid"=>"Madrid",
                    "Malaga"=>"Malaga",
                    "Barcelona"=>"Barcelona"
                ],
                "label"=>"Ciudad",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demo::class,
        ]);
    }
}
