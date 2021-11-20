<?php

declare(strict_types=1);

namespace App\Form\Search;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseSearchRequestType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'method' => Request::METHOD_GET,
                'csrf_protection' => false,
            ]);
    }
}