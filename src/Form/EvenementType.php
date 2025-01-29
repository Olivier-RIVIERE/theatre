<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Evenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nom_category',
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('nbPlace')
            ->add('prix')
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false, // Cela indique que ce champ n'est pas directement lié à une propriété de l'entité
                'required' => false, // Facultatif
                'multiple' => false, // Pas de téléchargement multiple
                'attr' => ['accept' => 'image/*'], // Limite le champ aux images
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
