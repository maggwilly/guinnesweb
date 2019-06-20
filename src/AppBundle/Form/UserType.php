<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class UserType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', 'text', array('required' => true,'label'=>'Nom'))
        ->add('ville', 'choice', array('required' => true,'choices'=>array(
          'Douala'=>'Douala',
          'Yaoundé'=>'Yaoundé',
          'Edea'=>'Edea',
          'Bafoussam'=>'Bafoussam',
          'Kribi'=>'Kribi',
          'Garoua'=>'Garoua',
          'Dschang'=>'Dschang',
          'Mbalmayo'=>'Mbalmayo',
          'Bertoua'=>'Bertoua',
          'Ngaoundere'=>'Ngaoundere',
          'Fouban'=>'Fouban',          
          'Maroua'=>'Maroua',
          'Nkongsamba'=>'Nkongsamba',
          'Sangmelima'=>'Sangmelima',
          'Bangangte'=>'Bangangte',
          'Ebolowa'=>'Ebolowa',
        )))
        ->add('username', 'text', array('required' => true,'label'=>'Identifiant'))
        ->add('phone', 'text', array('required' => true,'label'=>'telephone'))
        ->add('email', 'text', array('required' => true,'label'=>'Email'))
        ->add('secteur', 'text', array('required' => false,'label'=>'DEPOT'))
        ->add('campagne', EntityType::class, array(
            'choice_label' => 'nom',
            'class' => 'AppBundle:Campagne'
            ,'label'=>'Activité'))
        ->add('type', ChoiceType::class, array(
                                  'choices'  => array(
                                  'superviseur' => 'superviseur',
                                   'administrateur' => 'administrateur'
                                   ), 
                                  'multiple'=>false,
                                  'expanded'=>false,
                                  'attr'=>array('data-rel'=>'chosen'),
                                   ));
    }
    
        public function getParent()
        {
            return 'fos_user_registration';
        }

        public function getName()
        {
            return 'app_user_registration';
        }

}
