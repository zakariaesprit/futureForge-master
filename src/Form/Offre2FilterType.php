<?php

namespace App\Form;

use App\Entity\Offre2;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Offre2FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('offre2Type', ChoiceType::class, [
                'label' => 'Select offre type',
                'choices' => [
                    'TOUS' => null,
                    'MENSUELLE' => 'MENSUELLE',
                    'SEMESTRIELLE' => 'SEMESTRIELLE',
                    'ANNUELLE' => 'ANNUELLE',
                ],
                'expanded' => true,
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
