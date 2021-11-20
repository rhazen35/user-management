<?php

declare(strict_types=1);

namespace App\View\Pagination;

class PaginationView
{
    /**
     * @var object[]
     */
    public array $items;
    public int $totalHits;
    public int $currentPage;
    public int $totalPageCount;
    public int $itemsPerPage;

    /**
     * @param object[] $items
     */
    public function __construct(
        array $items,
        int $totalHits,
        int $currentPage,
        int $totalPageCount,
        int $itemsPerPage
    ) {
        $this->items = $items;
        $this->totalHits = $totalHits;
        $this->currentPage = $currentPage;
        $this->totalPageCount = $totalPageCount;
        $this->itemsPerPage = $itemsPerPage;
    }
}