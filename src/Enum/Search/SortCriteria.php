<?php

declare(strict_types=1);

namespace App\Enum\Search;

class SortCriteria
{
    const ASC = 'asc';
    const DESC = 'desc';

    /**
     * @return string[]
     */
    public static function getAvailableValues(): array
    {
        return [
            self::ASC,
            self::DESC,
        ];
    }
}