<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class LigneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('quantite')
        ->add('price')
        ->add('mecanisme')
        ->add('nombreRessources')
        ->add('frigo')
        ->add('gratuite')
        ->add('lineaire')
        ->add('stock')
        ->add('stockFinal')
        ->add('autre')
        ->add('affiche')
        ->add('invalide')
        ->add('commende', EntityType::class, array('class' => 'AppBundle:Commende'))
        ->add('produit', EntityType::class, array('class' => 'AppBundle:Produit'));
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ligne',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ligne';
    }


}
