<?php

declare(strict_types=1);

namespace App\View\User;

use App\Enum\Search\SortCriteria;
use App\Search\Request\PaginatedSearchRequestData;
use Symfony\Component\Validator\Constraints as Assert;

class ListSearchRequestData extends PaginatedSearchRequestData
{
    const BY_NAME_SORT_FIELD = "fullName";
    const BY_USER_NAME_SORT_FIELD = "username";
    const BY_EMAIL_SORT_FIELD = "email";
    const BY_LAST_LOGIN_SORT_FIELD = "lastLogin";
    const BY_CREATED_AT_SORT_FIELD = "createdAt";

    const SORT_FIELDS = [
        self::BY_NAME_SORT_FIELD,
        self::BY_USER_NAME_SORT_FIELD,
        self::BY_EMAIL_SORT_FIELD,
        self::BY_LAST_LOGIN_SORT_FIELD,
        self::BY_CREATED_AT_SORT_FIELD,
    ];

    /**
     * @Assert\Type("string")
     */
    public ?string $search = null;

    /**
     * @Assert\Type("array")
     * @Assert\Choice(
     *     ListSearchRequestData::SORT_FIELDS,
     *     multiple=true
     * )
     */
    public array $sortBy = [self::BY_CREATED_AT_SORT_FIELD];

    /**
     * @Assert\Type("array")
     * @Assert\Choice(
     *     callback={SortCriteria::class, "getAvailableValues"},
     *     multiple=true
     * )
     */
    public array $sortOrder = [SortCriteria::DESC];
}