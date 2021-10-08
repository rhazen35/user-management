<?php

declare(strict_types=1);

namespace App\Handler\Contract;

use App\Messenger\External\ExternalMessage;

interface HandlerInterface
{
    public function supports(ExternalMessage $externalMessage): bool;
}