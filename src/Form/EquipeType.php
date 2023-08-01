<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\Utilisateurs;
use App\Repository\UtilisateursRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
   

    ->add('nom_equipe')
    
    /*->add('managers', EntityType::class, [
        'class' => Utilisateurs::class,
        'query_builder' => function (UtilisateursRepository $er) {
            return $er->createQueryBuilder('u')
                ->orderBy('u.roles', 'ASC')
                ->where('u.roles LIKE :role_manager OR u.roles LIKE :role_user')
                ->setParameter('role_manager', '%"'.'ROLE_MANAGER'.'"%')
                ->setParameter('role_user', '%"'.'ROLE_USER'.'"%');
        },
        'choice_label' => 'Email',
        'label' => 'Manager',
        'placeholder' => 'Manager',
        'multiple' => true, // Allow multiple selections
        'expanded' => true, // Render checkboxes
        'required' => false, // Set this based on your requirements
    ]);
    */
    ->add('manager', EntityType::class, [
        'class' => Utilisateurs::class,
        'query_builder' => function (UtilisateursRepository $er) {
            return $er->createQueryBuilder('u')
            ->orderBy('u.roles', 'ASC')
           ->where('u.roles LIKE :role')
            ->setParameter('role', '%"'.'ROLE_MANAGER'.'"%');
            },
        'choice_label' => 'Email',
        'label' => 'Manager',
       'placeholder' => 'Manager',
        //'multiple'=>true
        ])
        ->add('membre', EntityType::class, [
            'class' => Utilisateurs::class,
            'query_builder' => function (UtilisateursRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.email', 'ASC')
                    ->where('u.roles LIKE :role')
                    ->setParameter('role', '%"'.'ROLE_USER'.'"%');
            },
            'choice_label' => 'email',
            'multiple' => true, // Permettre les sélections multiples
            'expanded' => true, // Rendre les cases à cocher
            'by_reference' => false,
            'label' => 'Membres',
        ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
