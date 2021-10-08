<?php

declare(strict_types=1);

namespace App\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class AuditMiddleware implements MiddlewareInterface
{
    private AuditLogger $auditLogger;

    public function __construct(AuditLogger $auditLogger)
    {
        $this->auditLogger = $auditLogger;
    }

    public function handle(
        Envelope $envelope,
        StackInterface $stack
    ): Envelope {
        $envelope = $stack
            ->next()
            ->handle(
                $envelope,
                $stack
            );

        $this
            ->auditLogger
            ->log($envelope);

        return $envelope;
    }
}