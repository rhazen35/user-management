<?php

declare(strict_types=1);

namespace App\Messenger\Middleware;

use App\Messenger\Stamp\Id\IdStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;

class AuditLogger
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $messengerAuditLogger)
    {
        $this->logger = $messengerAuditLogger;
    }

    public function log(Envelope $envelope): void
    {
        /** @var IdStamp $stamp */
        $stamp = $envelope->last(IdStamp::class);

        $context = [
            'id' => $stamp->getId(),
            'class' => get_class($envelope->getMessage())
        ];

        if ($envelope->last(ReceivedStamp::class)) {
            $this
                ->logger
                ->info(
                    '[{id}] Received {class}',
                    $context
                );
        }

        if ($envelope->last(SentStamp::class)) {
            $this
                ->logger
                ->info(
                    '[{id}] Sent {class}',
                    $context
                );
        }
    }
}