<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('TypeR')
            ->add('DescriptionR')
            ->add('Objet')
            ->add('DateR')
            ->add('etat')
        ;*/
        ->add('TypeR')
            ->add('Objet')
            ->add('DescriptionR', TextareaType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?!.*stupid).*$/i',
                        'message' => 'Your message contains banned words.',
                    ]),
                ],
            ])
            ->add('DateR', DateTimeType::class, [
                'date_label' => 'Starts On',
            ])
            ->add('etat')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
