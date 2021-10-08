<?php

/*************************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Ruben Hazenbosch <rh@braune-digital.com>, Braune Digital GmbH
 *
 *  All rights reserved
 ************************************************************************/

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