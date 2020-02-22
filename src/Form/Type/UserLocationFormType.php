<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserLocationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $cityChoices = [];
        $cityData = null;

        if (isset($options['data'])) {
            /** @var User $user */
            $user = $options['data'];

            if ($user->getCity()) {
                $title = (string) $user->getCity() . " ({$user->getCity()->getRegion()->getFullname()})";

                $cityData = $user->getCity()->getId();
                $cityChoices[$title] = $user->getCity()->getId();
            }
        }

        $builder
            ->add('city', ChoiceType::class, [
                'choice_translation_domain' => false,
                'choices'  => $cityChoices,
                'data'     => $cityData,
                'required' => false,
                'mapped'   => false,
            ])
            ->add('latitude', null, ['attr' => ['placeholder' => 'Latitude']])
            ->add('longitude', null, ['attr' => ['placeholder' => 'Longitude']])

            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn-success']])
        ;

        $builder->get('city')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'user_location';
    }
}
