<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\DTO\Worksheet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorksheetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach (new Worksheet() as $field => $value) {
            $builder->add($field);
        }

        $builder->add('save',   SubmitType::class, ['attr' => ['class' => 'btn-success']]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Worksheet::class,
            'translation_domain' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'worksheet';
    }
}
