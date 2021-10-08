<?php

declare(strict_types=1);

namespace App\Messenger\Stamp\Id;

use Symfony\Component\Uid\Uuid;

class IdStampFactory
{
    public function create(): IdStamp
    {
        $id = Uuid::v4()->toRfc4122();

        return new IdStamp($id);
    }
}