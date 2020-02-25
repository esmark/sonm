<?php

declare(strict_types=1);

namespace App\Shipping\Form;

use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressFormType extends AbstractType
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Order $order */
        $order = $options['data'];

        $locs = $this->em->getRepository(Address::class)->findBy(['user' => $order->getUser()]);

        if (empty($locs)) {
            throw new \Exception('Нету адресов');
        }

        $choices = [];
        foreach ($locs as $loc) {
            $choices[(string) $loc] = $loc->getId();
        }

        $builder
            ->add('address', ChoiceType::class, [
                'attr' => ['autofocus' => true],
                'choice_translation_domain' => false,
                'choices'  => $choices,
                'mapped' => false,
            ])

            ->add('choose', SubmitType::class, ['attr' => ['class' => 'btn-success']])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'shipping_method_address';
    }
}
