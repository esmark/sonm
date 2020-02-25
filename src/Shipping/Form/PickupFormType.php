<?php

declare(strict_types=1);

namespace App\Shipping\Form;

use App\Entity\PickUpLocation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PickupFormType extends AbstractType
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $locs = $this->em->getRepository(PickUpLocation::class)->findAll();

        $choices = [];
        foreach ($locs as $loc) {
            $choices[$loc->getAddress()] = $loc->getId();
//            $choices[(string) $loc] = $loc->getId();
        }

        $builder
            ->add('pick_up_location', ChoiceType::class, [
                'attr' => ['autofocus' => true],
                'choice_translation_domain' => false,
                'choices'  => $choices,
            ])

            ->add('choose', SubmitType::class, ['attr' => ['class' => 'btn-success']])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'shipping_method_pickup';
    }
}
