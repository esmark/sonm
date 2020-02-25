<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\TaxRate;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Product $product */
        $product = $options['data'];

        $builder
            ->add('title', null, ['attr' => ['autofocus' => true]])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.position', 'ASC')
                        ->addOrderBy('e.title', 'ASC');
                },
            ])
            ->add('taxRate', EntityType::class, [
                'class' => TaxRate::class,
                'query_builder' => function (EntityRepository $er) use ($product) {
                    return $er->createQueryBuilder('e')
                        ->join('e.cooperatives', 'coop', 'WITH', 'coop.id = :coop')
                        ->setParameter('coop', $product->getCooperative())
                        ->orderBy('e.percent', 'ASC');
                },
                'required' => false,
            ])
            ->add('is_physical')
            /*
            ->add('image_id', ImageFormType::class, [
                'mapped' => true,
                'required' => false,
                'label' => 'Image',
                'constraints' => [
                    new File([
                        'maxSize' => '8196k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ]
            ])
            */
            ->add('short_description', null, ['attr' => ['rows' => 2]])
            ->add('description', null, ['attr' => ['rows' => 10]])
            ->add('measure', ChoiceType::class, [
                'choices' => array_flip(Product::getMeasureChoiceValues()),
                'choice_translation_domain' => false,
            ])
            ->add('weight', null, ['attr' => ['placeholder' => 'in kilograms']])
            ->add('width',  null, ['attr' => ['placeholder' => 'in millimeters']])
            ->add('height', null, ['attr' => ['placeholder' => 'in millimeters']])
            ->add('depth',  null, ['attr' => ['placeholder' => 'in millimeters']])
            ->add('variants', CollectionType::class, [
                'entry_type' => ProductVariantFormType::class,
                'entry_options' => ['label' => '------'],
                'allow_add' => true,
            ])

            ->add('create', SubmitType::class, ['attr' => ['class' => 'btn-success']])
            ->add('update', SubmitType::class, ['attr' => ['class' => 'btn-success']])
            ->add('cancel', SubmitType::class, ['attr' => ['class' => 'btn-light', 'formnovalidate' => 'formnovalidate']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'product';
    }
}
