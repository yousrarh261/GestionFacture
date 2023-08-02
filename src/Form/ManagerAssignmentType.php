<?php
namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManagerAssignmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('managedUsers', EntityType::class, [
                'class' => Utilisateurs::class,
                'label' => 'Select users to manage',
                'multiple' => true,
                'expanded' => true,
                'choices' => $options['users'],
                'choice_label' => function (Utilisateurs $user) {
                    // Replace "name" with the property you want to display as the label
                    return $user->getName();
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
            'users' => null,
        ]);
    }
}



