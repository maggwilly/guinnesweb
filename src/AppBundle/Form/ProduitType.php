<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProduitType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom')
        ->add('cout')
        ->add('type')
        ->add('concurent', EntityType::class, array(
            'class' => 'AppBundle:Produit',
            'choice_label' => 'nom',
            'placeholder' => 'Aucun concurent',
            'required' => false,
            'empty_data'  => null,
        ))
        ->add('campagne', EntityType::class, array(
            'choice_label' => 'nom',
            'class' => 'AppBundle:Campagne'
            ,'label'=>'Activité'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_produit';
    }


}
