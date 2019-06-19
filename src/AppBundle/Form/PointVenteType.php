<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class PointVenteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
         ->add('nom', 'text', array('required' => true,'label'=>'Nom'))
         ->add('type', 'text', array('required' => true,'label'=>'Type'))
         ->add('nomGerant', 'text', array('required' => true,'label'=>'nom Gerant'))
         ->add('telGerant', 'text', array('required' => true,'label'=>'tel Gerant'))
         ->add('quartier', 'text', array('required' => true,'label'=>'Quartier'))
         ->add('latitude')
         ->add('longitude')
         ->add('description')
         ->add('user', EntityType::class, array(
            'choice_label' => 'nom',
            'class' => 'AppBundle:User'
            ,'label'=>'Sperviseur'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PointVente',
           'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_pointvente';
    }


}
