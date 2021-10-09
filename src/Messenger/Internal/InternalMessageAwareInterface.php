<?php

declare(strict_types=1);

namespace App\Messenger\Internal;

interface InternalMessageAwareInterface
{
    public function isInternal(): bool;
}