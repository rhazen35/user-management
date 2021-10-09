<?php

declare(strict_types=1);

namespace App\Messenger\Query;

use App\Messenger\Contract\QueryMessageInterface;
use App\Messenger\Message;

class QueryResult extends Message implements QueryMessageInterface
{
}