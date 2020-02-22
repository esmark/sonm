<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Cooperative;
use App\Entity\Geo\City;
use App\Entity\PickUpLocation;
use App\Entity\Program;
use App\Entity\TaxRate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CooperativeFormType extends AbstractType
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $cityChoices = [];
        $cityData = null;

        if (isset($options['data'])) {
            /** @var Cooperative $coop */
            $coop = $options['data'];

            if ($coop->getCity()) {
                $title = (string) $coop->getCity() . " ({$coop->getCity()->getRegion()->getFullname()})";

                $cityData = $coop->getCity()->getId();
                $cityChoices[$title] = $coop->getCity()->getId();
            }
        }

        $builder
            ->add('title', null, ['attr' => ['autofocus' => true]])
            ->add('slug', null,  ['attr' => ['placeholder' => 'Technical name, short, latin, with out spaces']])
            ->add('description', null, ['attr' => ['rows' => 8]])
            ->add('city', ChoiceType::class, [
                'choice_translation_domain' => false,
                'choices'  => $cityChoices,
                'data'     => $cityData,
                'required' => false,
                'mapped'   => false,
            ])
            ->add('address', null, ['attr' => ['placeholder' => 'укажите улицу и дом']])
            ->add('director')
            ->add('register_date')
            ->add('pick_up_locations', EntityType::class, [
                'class'         => PickUpLocation::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')->where('e.is_enabled = true')->orderBy('e.title', 'ASC');
                },
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ])
            /*
            ->add('programs', EntityType::class, [
                'class'         => Program::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')->where('e.is_enabled = true')->orderBy('e.title', 'ASC');
                },
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ])
            */
            ->add('taxRates', EntityType::class, [
                'class'         => TaxRate::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')->orderBy('e.percent', 'ASC');
                },
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ])
            ->add('taxRateDefault', EntityType::class, [
                'class'         => TaxRate::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')->orderBy('e.percent', 'ASC');
                },
                'required' => false,
            ])
            ->add('ogrn')
            ->add('inn')
            ->add('kpp')

            ->add('update', SubmitType::class, ['attr' => ['class' => 'btn-success']])
            ->add('cancel', SubmitType::class, ['attr' => ['class' => 'btn-light', 'formnovalidate' => 'formnovalidate']])
        ;

        $builder->get('city')->resetViewTransformers();
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
