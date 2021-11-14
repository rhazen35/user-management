<?php

declare(strict_types=1);

namespace App\Mercure\Authentication;

use App\Enum\Authentication\Channel;
use App\Mercure\Authentication\Contract\AbstractUpdateFactory;
use App\Messenger\Message;
use App\View\Validator\FormViolationView;
use Symfony\Component\Mercure\Update;

class InvalidCredentialsUpdateFactory extends AbstractUpdateFactory
{
    /**
     * @param array<FormViolationView> $violations
     */
    public function create(
        Message $message,
        array $violations
    ): Update {
        return new Update(
            $this->getTopic(Channel::USER_AUTHENTICATED),
            json_encode([
                'originatedMessageId' => $this->getOriginatedMessageId($message),
                'errors' => $violations
            ])
        );
    }
}