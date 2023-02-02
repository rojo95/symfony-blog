<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\PostType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CreatePostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Título',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class'=> 'form-control mb-3']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class'=> 'form-control mb-3']
            ])
            ->add('file', FileType::class, [
                'required' => false,
                'label' => 'Agregar Imagen',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class'=> 'form-control mb-3'],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Por favor cargue un archivo JPG/PNG valido.',
                    ])
                ],
            ])
            // ->add('type',EntityType::class, [
            //     'class' => PostType::class,
            //     'choice_label' => 'name',
            //     'label' => 'Tipo de Post'
            // ])
            ->add('type', ChoiceType::class, [
                'choices'  => PostType::TYPES,
                'label' => 'Tipo de Post',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class'=> 'form-select mb-3'],
            ])
            ->add('Crear', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
