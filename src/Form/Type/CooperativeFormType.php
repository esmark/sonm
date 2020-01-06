<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Cooperative;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CooperativeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['attr' => ['autofocus' => true]])
            ->add('name', null,  ['attr' => ['placeholder' => 'Technical name, short, latin, with out spaces']])
            ->add('description', null, ['attr' => ['rows' => 8]])
            ->add('ogrn')
            ->add('inn')
            ->add('kpp')
            ->add('address')
            ->add('director')
            ->add('update', SubmitType::class, ['attr' => ['class' => 'btn-success']])
            ->add('cancel', SubmitType::class, ['attr' => ['class' => 'btn-light', 'formnovalidate' => 'formnovalidate']])
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
        return 'cooperative';
    }
}
