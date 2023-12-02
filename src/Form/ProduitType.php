<?php

// src/Form/ProduitType.php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Typeproduit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prix')
            ->add('nom_produit')
            ->add('description')
            ->add('image',FileType::class,array("data_class"=>null))
            ->add('nombre_produit')
            ->add('idT',EntityType::class,options:[
                'class' => Typeproduit::class,
                'choice_label' =>'nom_type',
                'placeholder' =>'Select type',
                'required' => true
            ] )
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
