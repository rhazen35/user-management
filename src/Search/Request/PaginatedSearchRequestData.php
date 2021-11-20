<?php

declare(strict_types=1);

namespace App\Search\Request;

use Symfony\Component\Validator\Constraints as Assert;

class PaginatedSearchRequestData
{
    /**
     * @Assert\Range(min=1)
     */
    public int $page = 1;

    /**
     * @Assert\Range(
     *     min=1,
     *     max=100
     * )
     */
    public int $limit = 20;
}