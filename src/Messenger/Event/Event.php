<?php

declare(strict_types=1);

namespace App\Messenger\Event;

use App\Messenger\Contract\PublishMessageInterface;
use App\Messenger\Message;

final class Event extends Message implements PublishMessageInterface
{
}