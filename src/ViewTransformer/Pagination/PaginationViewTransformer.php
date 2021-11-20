<?php

declare(strict_types=1);

namespace App\ViewTransformer\Pagination;

use App\Search\Result\SearchResult;
use App\View\Pagination\PaginationView;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;

class PaginationViewTransformer
{
    /**
     * @param mixed[] $items
     */
    public function createFromArray(array $items, int $totalHits, int $currentPage, int $limit): PaginationView
    {
        $totalPages = (int) ceil($totalHits / $limit);

        return new PaginationView(
            $items,
            $totalHits,
            $currentPage,
            $totalPages,
            $limit,
        );
    }

    public function createFromSearchResult(SearchResult $searchResult, callable $itemConverter): PaginationView
    {
        $items = array_map(
            $itemConverter,
            $searchResult->items(),
        );

        return new PaginationView(
            $items,
            $searchResult->totalHits(),
            $searchResult->currentPage(),
            $searchResult->totalPages(),
            $searchResult->limit(),
        );
    }

    public function createFromAdapterAndRequest(AdapterInterface $adapter, int $page, int $limit): PaginationView
    {
        $pagination = new Pagerfanta($adapter);

        $pagination->setMaxPerPage($limit);
        $pagination->setCurrentPage($page);

        $items = $pagination->getCurrentPageResults();
        if ($items instanceof \Traversable) {
            $items = iterator_to_array($items);
        }

        $totalHits = $pagination->count() ?? 0;

        $totalPages = (int) ceil($totalHits / $limit);

        return new PaginationView(
            $items,
            $totalHits,
            $pagination->getCurrentPage(),
            $totalPages,
            $limit
        );
    }
}