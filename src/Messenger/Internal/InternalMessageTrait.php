<?php

declare(strict_types=1);

namespace App\Messenger\Internal;

trait InternalMessageTrait
{
    private bool $internal;

    public function isInternal(): bool
    {
        return $this->internal;
    }
}