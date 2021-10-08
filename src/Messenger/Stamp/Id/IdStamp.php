<?php

declare(strict_types=1);

namespace App\Messenger\Stamp\Id;

use Symfony\Component\Messenger\Stamp\StampInterface;

class IdStamp implements StampInterface
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}