<?php

declare(strict_types=1);

namespace App\View\Pagination;

use App\ViewTransformer\Pagination\PaginationViewTransformer;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

class PaginationViewFactory
{
    private PaginationViewTransformer $paginationViewTransformer;

    public function __construct(PaginationViewTransformer $paginationViewTransformer)
    {
        $this->paginationViewTransformer = $paginationViewTransformer;
    }

    public function fromQueryBuilderWithCallback(
        QueryBuilder $queryBuilder,
        callable $itemCallback,
        int $page,
        int $limit
    ): PaginationView {
        $adapter = new CallbackAdapterDecorator(
            new QueryAdapter($queryBuilder),
            $itemCallback
        );

        return $this
            ->paginationViewTransformer
            ->createFromAdapterAndRequest(
                $adapter,
                $page,
                $limit
            );
    }

    public function fromQueryWithCallback(
        Query $query,
        callable $itemCallback,
        int $page,
        int $limit
    ): PaginationView {
        $adapter = new CallbackAdapterDecorator(
            new QueryAdapter($query),
            $itemCallback
        );

        return $this
            ->paginationViewTransformer
            ->createFromAdapterAndRequest(
                $adapter,
                $page,
                $limit
            );
    }
}