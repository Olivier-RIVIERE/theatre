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
            TextField::new('description'),
            NumberField::new('prix'),
            NumberField::new('nbPlace'),
            DateTimeField::new('createdAt'),
            AssociationField::new('category', 'nom_category'),
            ImageField::new('image')
                ->setBasePath('/uploads/images') 
                ->setUploadDir('public/uploads/images')
                ->setRequired(false), 
        ];
    }
}
