<?php

declare(strict_types=1);

namespace App\Messenger;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class Dispatcher
{
    private MessageBusInterface $eventBus;
    private HubInterface $hub;

    public function __construct(
        MessageBusInterface $eventBus,
        HubInterface $hub
    ) {
        $this->eventBus = $eventBus;
        $this->hub = $hub;
    }

    public function dispatchEventAndUpdate(
        Envelope $envelope,
        Update $update
    ): void {
        $this
            ->eventBus
            ->dispatch($envelope);

        $this
            ->hub
            ->publish($update);
    }
}