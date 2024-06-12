<?php

namespace App\DataFixtures;

use App\Entity\Datatype;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DatatypeFixtures extends Fixture
{

  

    public function load(ObjectManager $manager)
    {


        $com1 = new DataType();
        $com1->setDenomination('boolean');

        $manager->persist($com1);

        $com2 = new DataType();
        $com2->setDenomination('integer');

        $manager->persist($com2);


        $com3 = new Datatype();
        $com3->setDenomination(' string');
        $manager->persist($com3);

        $com4 = new Datatype();
        $com4->setDenomination(' float');
        $manager->persist($com4);

        $com5 = new Datatype();
        $com5->setDenomination(' double');
        $manager->persist($com5);

        $com6 = new Datatype();
        $com6->setDenomination(' char');
        $manager->persist($com6);

        $manager->flush();
    }


}