<?php

namespace App\DataFixtures;

use App\Entity\NTA;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NTAFixtures extends Fixture
{

  

    public function load(ObjectManager $manager)
    {


        $com1 = new NTA();
        $com1->setDenomination('Interna');

        $manager->persist($com1);

        $com2 = new NTA();
        $com2->setDenomination('Interactiva');

        $manager->persist($com2);


        $com3 = new NTA();
        $com3->setDenomination('Implementación');
        $manager->persist($com3);

       


        $manager->flush();
    }


}