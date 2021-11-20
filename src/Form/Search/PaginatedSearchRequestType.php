<?php

declare(strict_types=1);

namespace App\Form\Search;

use App\Search\Request\PaginatedSearchRequestData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaginatedSearchRequestType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('limit', NumberType::class, ['scale' => 0])
            ->add('page', NumberType::class, ['scale' => 0]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => PaginatedSearchRequestData::class,
            ]);
    }

    public function getParent(): string
    {
        return BaseSearchRequestType::class;
    }
}