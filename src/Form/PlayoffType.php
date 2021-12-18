<?php

namespace App\Form;

use App\Entity\Playoff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayoffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('teams')
            ->add('quarter1')
            ->add('quarter2')
            ->add('quarter3')
            ->add('quarter4')
            ->add('half1')
            ->add('half2')
            ->add('final')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playoff::class,
        ]);
    }
}
