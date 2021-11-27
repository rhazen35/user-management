<?php

declare(strict_types=1);

namespace App\Form\User;

use App\Enum\Search\SortCriteria;
use App\Form\Search\PaginatedSearchRequestType;
use App\View\User\ListSearchRequestData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListSearchType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('search', TextType::class)
            ->add(
                'sortBy',
                ChoiceType::class,
                [
                    'choices' => ListSearchRequestData::SORT_FIELDS,
                    'multiple' => true,
                ]
            )
            ->add(
                'sortOrder',
                ChoiceType::class,
                [
                    'choices' => SortCriteria::getAvailableValues(),
                    'multiple' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(['data_class' => ListSearchRequestData::class]);
    }

    public function getParent(): string
    {
        return PaginatedSearchRequestType::class;
    }
}