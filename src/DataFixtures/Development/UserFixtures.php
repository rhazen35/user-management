<?php

declare(strict_types=1);

namespace App\DataFixtures\Development;

use App\Entity\User\User;
use App\Messenger\Event\User\UserCreatedEventFactory;
use App\Model\User\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Generator;
use Symfony\Component\Messenger\MessageBusInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    const GROUP = "user";

    private UserFixtureDataFactory $userFixtureDataFactory;
    private Factory $factory;
    private UserCreatedEventFactory $userCreatedEventFactory;
    private MessageBusInterface $eventBus;

    public function __construct(
        UserFixtureDataFactory $userFixtureDataFactory,
        Factory $factory,
        UserCreatedEventFactory $userCreatedEventFactory,
        MessageBusInterface $eventBus
    ) {

        $this->userFixtureDataFactory = $userFixtureDataFactory;
        $this->factory = $factory;
        $this->userCreatedEventFactory = $userCreatedEventFactory;
        $this->eventBus = $eventBus;
    }

    private function generateAdminUserFixtureData(): UserFixtureData
    {
        return $this
            ->userFixtureDataFactory
            ->create(
                "System",
                "Administrator",
                "admin",
                "administrator@skeme.com",
                "admin"
            );
    }

    private function generateUserFixtureData(): Generator {
        yield $this
            ->userFixtureDataFactory
            ->create("Johan", "Klostermann", "johanklostermann", "johanklostermann@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Reinier", "van der Hoff", "reiniervanderhof", "reiniervanderhof@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Gabriel", "Reid", "gabrielreid", "gabrielreid@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Lucas", "Sörensen", "lucassorensen", "lucassorensen@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Lucas", "Hoffman", "lucashoffman", "lucashoffman@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Edgar", "Neal", "edgarneal", "edgarneal@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Tim", "Östrom", "timostrom", "timostrom@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Jeppe", "Hansen", "jeppehansen", "jeppehansen@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Sebastian", "Forsberg", "sebastianforsberg", "sebastianforsberg@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Jeremy", "Meuser", "jeremymeuser", "jeremymeuser@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Jared", "Burton", "jaredburton", "jaredburton@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Max", "Fontai", "maxfontai", "maxfontai@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Samantha", "Burton", "samanthaburton", "samanthaburton@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Laura", "Mitchell", "lauramitchell", "lauramitchell@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Pilar", "Fernandez", "pilarfernandez", "pilarfernandez@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Caroline", "Rasmussen", "carolinerasmussen", "carolinerasmussen@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Willie", "Gibson", "willygibson", "willygibson@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Anuschka", "Vogel", "anuschkavogel", "anuschkavogel@skeme.com", "TestTest20-");

        yield $this
            ->userFixtureDataFactory
            ->create("Melissa", "Lanz", "melissalanz", "melissalanz@skeme.com", "TestTest20-");
    }

    public function load(ObjectManager $manager): void
    {
        $adminUserData = $this->generateAdminUserFixtureData();
        $adminUser = $this
            ->factory
            ->createFromFixtureData($adminUserData);

        $manager->persist($adminUser);
        $manager->flush();
        $this->dispatchEvent($adminUser);

        foreach ($this->generateUserFixtureData() as $userFixtureData) {
            $user = $this
                ->factory
                ->createFromFixtureData($userFixtureData);

            $manager->persist($user);
            $manager->flush();

            $this
                ->dispatchEvent($user);
        }
    }

    private function dispatchEvent(User $user): void
    {
        $event = $this
            ->userCreatedEventFactory
            ->create(
                $user,
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