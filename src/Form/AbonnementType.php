<?php

namespace App\Form;

use App\Entity\Abonnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('image', FileType::class, ['mapped' => false])
            ->add('email')
            ->add('identifiant')
            ->add('cin')
            // ->add('type')
          /*  ->add('dated', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'datepicker'],
            ])
            ->add('datef', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'datepicker'],
            ])*/
            // ->add('prix')
            // ->add('idOffre', null, [
            //     'label' => 'Offre',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}
