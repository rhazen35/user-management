<?php

/*************************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Ruben Hazenbosch <rh@braune-digital.com>, Braune Digital GmbH
 *
 *  All rights reserved
 ************************************************************************/

declare(strict_types=1);

namespace App\Handler\User;

use App\Enum\User\Channel;
use App\Form\Type\User\CreateUserType;
use App\Handler\Contract\HandlerInterface;
use App\Messenger\External\ExternalMessage;
use App\Model\User\CreateUserData;
use App\Model\User\CreateUserDataFactory;
use App\Model\User\Manager;
use Symfony\Component\Form\FormFactoryInterface;

class CreateUserHandler implements HandlerInterface
{
    private FormFactoryInterface $formFactory;
    private CreateUserDataFactory $createUserDataFactory;
    private CreateUserValidatorHandler $createUserValidatorHandler;
    private Manager $manager;
    private UserCreatedHandler $userCreatedHandler;

    public function __construct(
        FormFactoryInterface $formFactory,
        CreateUserDataFactory $createUserDataFactory,
        CreateUserValidatorHandler $createUserValidatorHandler,
        Manager $manager,
        UserCreatedHandler $userCreatedHandler
    ) {
        $this->formFactory = $formFactory;
        $this->createUserDataFactory = $createUserDataFactory;
        $this->createUserValidatorHandler = $createUserValidatorHandler;
        $this->manager = $manager;
        $this->userCreatedHandler = $userCreatedHandler;
    }

    public function supports(ExternalMessage $externalMessage): bool
    {
        if (Channel::CREATE_USER !== $externalMessage->getChannel()) {
            return false;
        }

        return true;
    }

    public function __invoke(ExternalMessage $externalMessage): void
    {
        $createUserData = $this
            ->createUserDataFactory
            ->createArrayFromPayload($externalMessage->getPayload());

        $form = $this
            ->formFactory
            ->create(CreateUserType::class);

        $form->submit($createUserData);

        $isValid = $this
            ->createUserValidatorHandler
            ->__invoke(
                $externalMessage,
                $form
            );

        if (!$isValid) {
            return;
        }

        /** @var CreateUserData $data */
        $data = $form->getData();

        $user = $this
            ->manager
            ->createAndFlush($data);

        $this
            ->userCreatedHandler
            ->__invoke(
                $user,
                $externalMessage
            );
    }
}