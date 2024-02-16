<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FormCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $wish = $options['wish'] ?? null;

        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Write your title',
                    'class' => 'form-control bg-light form-control rounded'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Write your description',
                    'class' => 'form-control bg-light form-control rounded',
                    'rows' => 5
                ]
            ])
            ->add('author', TextType::class, [
                'label' => 'Whisher',
                'required' => false,
                'attr' => [
                    'maxlength' => 50,
                    'class' => 'form-control bg-light form-control rounded',
                    'placeholder' => 'Wisher\'s name',
                ]
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'Published',
                'required' => false,
                'attr' => [
                    'checked' => 'checked',
                    'class' => 'form-check-input'
                ]
            ])
            ->add('deleteImg', CheckboxType::class, [
                'label' => 'Delete Image',
                'required' => false,
                'mapped' => false
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-warning border m-2'
                ]
            ])
            ->add('posterFile', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => "Ce format n'est pas autorisé",
                        'maxSizeMessage' => "Ce fichier est trop lourd",
                ])
            ],
                'help' => 'Upload an image (JPEG, JPG, PNG)',

            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'Choose your category',
                'choice_label' => 'name',
                'label' => 'catégorie',
                'query_builder' => function(CategoryRepository $CategoryRepository) {
                    return $CategoryRepository->createQueryBuilder('cat')->addOrderBy('cat.name', 'ASC');
                }
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
