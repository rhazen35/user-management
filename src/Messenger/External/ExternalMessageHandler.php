<?php

declare(strict_types=1);

namespace App\Messenger\External;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ExternalMessageHandler implements MessageHandlerInterface
{
    public function __invoke(ExternalMessage $externalMessage): void
    {

    }
}