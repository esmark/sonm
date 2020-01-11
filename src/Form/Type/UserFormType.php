<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, ['attr' => ['autofocus' => true]])
            ->add('lastname')
            ->add('patronymic')
            ->add('has_relative', CheckboxType::class, [
                'label' => 'Есть ли у вас родственники в Кооперативе?',
                'required' => false,
                'translation_domain' => false,
            ])
            ->add('phone')
            ->add('social_links', TextareaType::class, [
                'attr' => ['rows' => 5],
                'required' => false,
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'Ваше положение',
                'expanded' => true,
                'choices'  => [
                    'Производитель' => 'Производитель',
                    'Потребитель' => 'Потребитель',
                    'Другое' => 'Другое',
                ],
                'choice_translation_domain' => false,
                'translation_domain' => false,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Вы сейчас',
                'expanded' => true,
                'choices'  => [
                    'Пенсионер' => 'Пенсионер',
                    'Домохозяйка' => 'Домохозяйка',
                    'Безработный' => 'Безработный',
                    'Предприниматель (ООО, ИП)' => 'Предприниматель (ООО, ИП)',
                    'Фермер (КФХ, ЛПХ)' => 'Фермер (КФХ, ЛПХ)',
                    'Профессия' => 'Профессия',
                    'Другое' => 'Другое',
                ],
                'choice_translation_domain' => false,
                'translation_domain' => false,
            ])
            ->add('education', ChoiceType::class, [
                'expanded' => true,
                'choices'  => [
                    'Высшее' => 'Высшее',
                    'Среднее специальное' => 'Среднее специальное',
                    'Среднее' => 'Среднее',
                    'Другое' => 'Другое',
                ],
                'choice_translation_domain' => false,
            ])
            ->add('schools', TextareaType::class, [
                'attr' => ['rows' => 5],
                'required' => false,
            ])
            ->add('skills', TextareaType::class, [
                'attr' => ['rows' => 5],
                'required' => false,
            ])
            ->add('activity', ChoiceType::class, [
                'label' => 'Каким направлением хозяйственной деятельности хотел бы заняться?',
                'expanded' => true,
                'choices'  => [
                    'Общественное питание' => 'Общественное питание',
                    'Заготовка сельхозпродуктов и сырья' => 'Заготовка сельхозпродуктов и сырья',
                    'Подсобное хозяйство' => 'Подсобное хозяйство',
                    'Капитальное строительство' => 'Капитальное строительство',
                    'Транспортное обслуживание кооперативных предприятий' => 'Транспортное обслуживание кооперативных предприятий',
                    'Производство' => 'Производство',
                    'Торговля' => 'Торговля',
                    'Услуги' => 'Услуги',
                    'Другое' => 'Другое',
                ],
                'choice_translation_domain' => false,
                'translation_domain' => false,
            ])
            ->add('participate', ChoiceType::class, [
                'label' => 'Выберите направление, в котором готовы принять участие?',
                'expanded' => true,
                'choices'  => [
                    'Создание кооператива (артели, общины)' => 'Создание кооператива (артели, общины)',
                    'Руководители проектов (целевых потребительских программ в кооперативе)' => 'Руководители проектов (целевых потребительских программ в кооперативе)',
                    'Бухгалтерия' => 'Бухгалтерия',
                    'Экономисты' => 'Экономисты',
                    'Юристы' => 'Юристы',
                    'ИТ – программирование, дизайн' => 'ИТ – программирование, дизайн',
                    'Пропаганда – журналистика, копирайтинг, PR, маркетинг, монтаж видео' => 'Пропаганда – журналистика, копирайтинг, PR, маркетинг, монтаж видео',
                    'Ведущие на видеоканал' => 'Ведущие на видеоканал',
                    'Активисты' => 'Активисты',
                    'Хочу просто стать пайщиком' => 'Хочу просто стать пайщиком',
                    'Другое' => 'Другое',
                ],
                'choice_translation_domain' => false,
                'translation_domain' => false,
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
        return 'user';
    }
}
