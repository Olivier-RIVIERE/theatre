<?php

namespace App\Controller\Admin;

use App\Entity\Evenement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class EvenementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Evenement::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('valide'),
            TextField::new('titre'),
            TextEditorField::new('description'),
            NumberField::new('prix'),
            NumberField::new('nbPlace'),
            DateTimeField::new('createdAt'),
            AssociationField::new('category', 'nom_category'),
            ImageField::new('image') // Ajouter le champ image
                ->setBasePath('/uploads/images') // Chemin où les images seront accessibles dans le frontend
                ->setUploadDir('public/uploads/images') // Répertoire où les images seront stockées
                ->setRequired(false), // Facultatif si l'image est optionnelle
        ];
    }
}
