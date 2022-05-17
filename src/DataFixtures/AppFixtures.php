<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(); 
        $classe = ['GLAR3', 'L2', 'GLAR3', 'MASTER2'];
        
        $i = 1;
         while($i<=50){
            $user = new Users();
             $user->setPrenom($faker->firstName())
                ->setNom($faker->lastName())
                ->setAge(rand(20, 29))
                ->setClasse($classe[rand(0, 3)]);
                $manager->persist($user);
                $i++;
         }

        $manager->flush();
    }
}
