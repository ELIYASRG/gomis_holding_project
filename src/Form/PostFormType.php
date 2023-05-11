<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Category;
use App\Form\ImageFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                'placeholder' => 'Choisissez une catégorie'
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('description', TextareaType::class)
            ->add('tags', EntityType::class, [
                // looks for choices from this entity
                'class' => Tag::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                
                'placeholder' => 'Choisissez un tag',
                
                // used to render a select box, check boxes or radios
                'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('description', TextareaType::class)
            ->add('imageFile', VichImageType::class)
            // ->add('images' , CollectionType::class, [
            //     'entry_type' => ImageFormType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'prototype' => true
            //     // 'required' => false,
            //     // 'label'=>false,
            //     // 'by_reference' => false,
            //     // 'disabled' => false,
            // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
