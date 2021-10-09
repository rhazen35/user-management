<?php

declare(strict_types=1);

namespace App\Messenger\Contract;

use App\Messenger\Message;
use App\Messenger\Stamp\Amqp\AmqpStampFactory;
use App\Messenger\Stamp\Id\IdStamp;
use App\Messenger\Stamp\Id\IdStampFactory;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;

class AbstractMessageFactory
{
    private AmqpStampFactory $amqpStampFactory;
    private IdStampFactory $idStampFactory;

    public function __construct(
        AmqpStampFactory $amqpStampFactory,
        IdStampFactory $idStampFactory
    ) {
        $this->amqpStampFactory = $amqpStampFactory;
        $this->idStampFactory = $idStampFactory;
    }

    protected function getAmqpStamp(string $channel): AmqpStamp
    {
        return $this
            ->amqpStampFactory
            ->create($channel);
    }

    protected function getIdStamp(): IdStamp
    {
        return $this
            ->idStampFactory
            ->create();
    }

    protected function getOriginatedMessageId(Message $message): ?string
    {
        return $message->getOriginatedMessageId() ?? $message->getMessageId();
    }
}