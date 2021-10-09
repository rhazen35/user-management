<?php

declare(strict_types=1);

namespace App\Validator\Messenger\Message;

use App\Enum\Authentication\WhiteList;
use App\Messenger\Internal\InternalMessageAwareInterface;
use App\Messenger\Message;
use App\Messenger\Token\TokenAwareInterface;
use App\Validator\Authentication\Token\TokenValidator;
use Doctrine\ORM\NonUniqueResultException;

class MessageValidator
{
    private TokenValidator $tokenValidator;

    public function __construct(TokenValidator $tokenValidator)
    {
        $this->tokenValidator = $tokenValidator;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(Message $message): bool
    {
        if (in_array($message->getChannel(), WhiteList::getList())) {
            return true;
        }

        if ($message instanceof InternalMessageAwareInterface && $message->isInternal()) {
            return true;
        }

        if (!$message instanceof TokenAwareInterface) {
            return true;
        }

        return $this
            ->tokenValidator
            ->__invoke($message);
    }
}