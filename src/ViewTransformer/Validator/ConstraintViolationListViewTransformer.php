<?php

/*************************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Ruben Hazenbosch <rh@braune-digital.com>, Braune Digital GmbH
 *
 *  All rights reserved
 ************************************************************************/

declare(strict_types=1);

namespace App\ViewTransformer\Validator;

use App\View\Validator\ConstraintViolationListView;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationListViewTransformer
{
    private ConstraintViolationViewTransformer $constraintViolationViewTransformer;

    /**
     * ConstraintViolationViewTransformer constructor.
     */
    public function __construct(ConstraintViolationViewTransformer $constraintViolationViewTransformer)
    {
        $this->constraintViolationViewTransformer = $constraintViolationViewTransformer;
    }

    /**
     * @param ConstraintViolationListInterface<ConstraintViolation> $constraintViolationList
     */
    public function transform(ConstraintViolationListInterface $constraintViolationList): ConstraintViolationListView
    {
        $violationViews = new ArrayCollection();

        foreach ($constraintViolationList as $constraintViolation) {
            $violationViews->add(
                $this
                    ->constraintViolationViewTransformer
                    ->transform($constraintViolation)
            );
        }

        return new ConstraintViolationListView($violationViews);
    }
}