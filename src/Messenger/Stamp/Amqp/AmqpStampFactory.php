<?php

declare(strict_types=1);

namespace App\Messenger\Stamp\Amqp;

use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;

class AmqpStampFactory
{
    public function create(string $channel): AmqpStamp
    {
        return new AmqpStamp($channel);
    }
}