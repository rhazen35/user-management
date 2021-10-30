<?php

declare(strict_types=1);

namespace App\Provider\DateTime;

use DateTimeImmutable;

class DateTimeProvider
{
    public function getCurrentDateTimeImmutable(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}