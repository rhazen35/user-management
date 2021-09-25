<?php

declare(strict_types=1);

namespace App\DataFixtures\Development;

use App\Model\User\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Generator;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    const GROUP = "user";

    private Factory $factory;

    public function __construct(Factory $factory)
    {

        $this->factory = $factory;
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
    }

    public static function getGroups(): array
    {
        return[self::GROUP];
    }
}