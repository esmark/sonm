<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Cooperative;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CooperativeCreateFormType extends CooperativeFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('update')
            ->add('create', SubmitType::class, ['attr' => ['class' => 'btn-success']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cooperative::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'cooperative_create';
    }
}
