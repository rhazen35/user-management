<?php

declare(strict_types=1);

namespace App\Messenger\External;

use App\Messenger\Contract\SubscribeMessageInterface;
use App\Messenger\Message;

final class ExternalMessage extends Message implements SubscribeMessageInterface
{
}