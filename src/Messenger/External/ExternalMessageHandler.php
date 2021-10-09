<?php

declare(strict_types=1);

namespace App\Messenger\External;

use App\Handler\Contract\HandlerInterface;
use App\Messenger\Exception\NoHandlerFoundException;
use App\Messenger\Message;
use App\Validator\Messenger\Message\MessageValidator;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ExternalMessageHandler implements MessageHandlerInterface
{
    private iterable $handlers;
    private MessageValidator $messageValidator;

    public function __construct(
        iterable $handlers,
        MessageValidator $messageValidator
    ) {
        $this->handlers = $handlers;
        $this->messageValidator = $messageValidator;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(Message $message): void
    {
        $isValid = $this
            ->messageValidator
            ->__invoke($message);

        if (!$isValid) {
            return;
        }

        $isHandled = false;
        /** @var HandlerInterface $handler */
        foreach ($this->handlers as $handler) {
            if ($handler->supports($message)) {
                $handler->__invoke($message);

                $isHandled = true;
                break;
            }
        }

        if (!$isHandled) {
            throw new NoHandlerFoundException(
                sprintf(
                    'No handler could be found for channel: %s.',
                    $message->getChannel()
                )
            );
        }
    }
}