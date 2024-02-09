<?php

namespace App\Form;

use App\Entity\Wish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
            'attr'=> [
                'placeholder' => 'New Wish',
                'size' => 81
            ]
        ])
            ->add('description', TextareaType::class,[
                'attr'=> [
                    'placeholder' => 'Write your description',
                    'cols' => 83
                ]
            ])

            ->add('author', TextType::class, [
                'label' =>'User name',
                'attr' => [
                    'maxlength' => 50,
                    'class' => 'special-class',
                    'placeholder' => 'Wisher\'s name',
                    'size' => 81
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                'value' => 'Engregistrer'
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
