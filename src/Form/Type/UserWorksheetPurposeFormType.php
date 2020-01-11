<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserWorksheetPurposeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('purposeTake', TextareaType::class, [
                'attr' => ['rows' => 15],
                'required' => false,
            ])
            ->add('purposeGive', TextareaType::class, [
                'attr' => ['rows' => 15],
                'required' => false,
            ])

            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn-success']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'user_worksheet_purpose';
    }
}
