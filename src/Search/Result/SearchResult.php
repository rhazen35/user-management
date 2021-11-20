<?php

declare(strict_types=1);

namespace App\Search\Result;

use App\Search\Request\PaginatedSearchRequestData;

class SearchResult
{
    private PaginatedSearchRequestData $data;

    /**
     * @var array<array>
     */
    private array $rawResult;

    /**
     * @var array<object>
     */
    private array $items;

    /**
     * @param array<array>  $rawResult
     * @param array<object> $items
     */
    public function __construct(
        PaginatedSearchRequestData $data,
        array $rawResult,
        array $items
    ) {
        $this->data = $data;
        $this->rawResult = $rawResult;

        // Ensure $items not to be an associative array
        $this->items = array_values($items);
    }

    public function currentPage(): int
    {
        return $this->data->page;
    }

    public function limit(): int
    {
        return $this->data->limit;
    }

    public function totalHits(): int
    {
        return $this->rawResult['hits']['total']['value'];
    }

    public function currentPageOffsetStart(): int
    {
        return 0 !== $this->totalHits()
            ? (($this->currentPage() - 1) * $this->limit()) + 1
            : 0;
    }

    public function currentPageOffsetEnd(): int
    {
        return min($this->currentPage() * $this->limit(), $this->totalHits());
    }

    public function totalPages(): int
    {
        return (int) ceil($this->totalHits() / $this->limit());
    }

    /**
     * @return array<array>
     */
    public function rawResult(): array
    {
        return $this->rawResult;
    }

    /**
     * @return array<object>
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * @return mixed[]
     */
    public function getAggregation(string $name): array
    {
        return $this->rawResult()['aggregations'][$name];
    }
}