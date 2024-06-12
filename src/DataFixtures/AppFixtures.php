<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AppFixtures extends Fixture
{

    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;

    }

    public function load(ObjectManager $manager): void
    {
        $ad = new User();
        $ad->setEmail('admin@admin.com');
        $ad->setRoles(['ROLE_ADMIN']);
        $ad->setPassword($this->encoderFactory->getEncoder(User::class)->encodePassword('admin', 'null'));
        $ad->setName('Admin Admin');
        $manager->persist($ad);

        $ad2 = new User();
        $ad2->setEmail('admin2@admin2.com');
        $ad2->setRoles(['ROLE_ADMIN']);
        $ad2->setPassword($this->encoderFactory->getEncoder(User::class)->encodePassword('admin2', 'null'));
        $ad2->setName('Admin Admin2');
        $manager->persist($ad2);


        $ad3 = new User();
        $ad3->setEmail('student@student.com');
        $ad3->setRoles(['ROLE_USER']);
        $ad3->setPassword($this->encoderFactory->getEncoder(User::class)->encodePassword('student', 'null'));
        $ad3->setName('Student Student');
        $manager->persist($ad3);

        $manager->flush();
    }
}
