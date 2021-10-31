<?php

declare(strict_types=1);

namespace App\DataFixtures\Development;

use App\Messenger\Event\User\UserCreatedEventFactory;
use App\Model\User\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\MessageBusInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    const GROUP = "user";

    private Factory $factory;
    private UserCreatedEventFactory $userCreatedEventFactory;
    private MessageBusInterface $eventBus;

    public function __construct(
        Factory $factory,
        UserCreatedEventFactory $userCreatedEventFactory,
        MessageBusInterface $eventBus
    ) {

        $this->factory = $factory;
        $this->userCreatedEventFactory = $userCreatedEventFactory;
        $this->eventBus = $eventBus;
    }

    private function generateAdminUserData(): array
    {
        return [
            'firstName' => "System",
            'lastName' => "Administrator",
            'username' => "admin",
            'email' => "system.administrator@company.com",
            'password' => "admin",
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $adminUserData = $this->generateAdminUserData();
        $adminUser = $this
            ->factory
            ->createFromArray($adminUserData);

        $manager->persist($adminUser);
        $manager->flush();

        $event = $this
            ->userCreatedEventFactory
            ->create(
                $adminUser,
                null
            );

        $this
            ->eventBus
            ->dispatch($event);
    }

    public static function getGroups(): array
    {
        return[self::GROUP];
    }
}