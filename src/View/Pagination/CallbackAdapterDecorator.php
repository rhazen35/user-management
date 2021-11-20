<?php

declare(strict_types=1);

namespace App\View\Pagination;

use Closure;
use Pagerfanta\Adapter\AdapterInterface;

class CallbackAdapterDecorator implements AdapterInterface
{
    private AdapterInterface $decorated;
    private Closure $closure;

    public function __construct(
        AdapterInterface $decorated,
        callable $closure
    ) {
        $this->decorated = $decorated;

        if (!$closure instanceof Closure) {
            $closure = Closure::fromCallable($closure);
        }

        $this->closure = $closure;
    }

    public function getNbResults(): int
    {
        return $this->decorated->getNbResults();
    }

    /**
     * @inheritDoc
     */
    public function getSlice(
        int $offset,
        int $length
    ): iterable {
        $innerSlice = $this->decorated->getSlice($offset, $length);

        $closure = $this->closure;

        foreach ($innerSlice as $key => $item) {
            yield $key => ($closure($item, $key));
        }
    }
}