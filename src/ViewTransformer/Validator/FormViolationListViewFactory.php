<?php

declare(strict_types=1);

namespace App\ViewTransformer\Validator;

use App\View\Validator\FormViolationView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class FormViolationListViewFactory
{
    private ViolationViewFactory $violationViewFactory;

    public function __construct(ViolationViewFactory $violationViewFactory)
    {
        $this->violationViewFactory = $violationViewFactory;
    }

    /**
     * @param ConstraintViolationListInterface<ConstraintViolation> $violationList
     *
     * @return array<FormViolationView>
     */
    public function __invoke(ConstraintViolationListInterface $violationList): array
    {
        return array_map(
            [$this->violationViewFactory, '__invoke'],
            iterator_to_array($violationList)
        );
    }

    /**
     * @param FormInterface<FormInterface> $form
     *
     * @return array<FormViolationView>
     */
    public function flattenForForm(FormInterface $form): array
    {
        $violations = [];

        $errorIterator = $form->getErrors(true, true);

        foreach ($errorIterator as $error) {
            $cause = $error->getCause();

            if (!$cause instanceof ConstraintViolation) {
                continue;
            }

            /** @var FormInterface $origin */
            $origin = $error->getOrigin();

            $violations[] = $this
                ->violationViewFactory
                ->fromFormError(
                    $cause,
                    $origin->getName()
                );
        }

        return $violations;
    }
}