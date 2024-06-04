<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = (new Users)
            ->setName('test')
            ->setFirstName('admin')
            ->setEmail('admin@test.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword(new Users, 'Test1234!'));

        $manager->persist($user);

        $user = (new Users)
            ->setName('test')
            ->setFirstName('user')
            ->setEmail('user@test.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->hasher->hashPassword(new Users, 'Test1234!'));

        $manager->persist($user);

        $manager->flush();
    }
}
