<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', null, ['attr' => ['autofocus' => true]])
            ->add('firstname')
            ->add('patronymic')
            ->add('phone')
            ->add('postcode')
            ->add('province')
            ->add('city')
            ->add('street')
            ->add('house')
            ->add('flat')

            ->add('create', SubmitType::class, ['attr' => ['class' => 'btn-success']])
            ->add('update', SubmitType::class, ['attr' => ['class' => 'btn-success']])
            ->add('cancel', SubmitType::class, ['attr' => ['class' => 'btn-light', 'formnovalidate' => 'formnovalidate']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'address';
    }
}
