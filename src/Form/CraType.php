<?php

namespace App\Form;

use App\Entity\Cra;
use App\Entity\CodeProjet;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Define French month names
        $frenchMonthNames = [
            'Janvier',
            'Février',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Août',
            'Septembre',
            'Octobre',
            'Novembre',
            'Décembre',
        ];

        // Get current year and month
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('m');

        // Create array of months starting from the current month and year
        $months = [];
        for ($i = $currentMonth - 1; $i <= $currentMonth + 1; $i++) {
            $month = $frenchMonthNames[$i - 1];
            $year = date('Y', mktime(0, 0, 0, $i, 1, $currentYear));
            $months[$month . ' ' . $year] = $month . ' ' . $year;
        }


  $builder  ->add('codeProjet', EntityType::class, [
            'required' => false,
            'class' => CodeProjet::class,
            'choice_label' => 'code_projet',
            'multiple' => false,
            'label' => 'Code de Projet',
            'placeholder' => '',
        ])
        // Add the months to the form
     ->add('month', ChoiceType::class, [
            'choices' => $months,
            'label' => 'Mois',
            'data' => current($months),
            'attr' => [
                'class' => 'form-control',
            ],
        ])

        
    
        ->add('save', SubmitType::class, ['label' => 'Créer']); 
        
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cra::class,
        ]);
    }
}