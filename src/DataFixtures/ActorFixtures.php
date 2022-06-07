<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Actor;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        /**
         * L'objet $faker que tu récupère est l'outil qui va te permettre 
         * de te générer toutes les données que tu souhaites
         */
        $numberPrograms = 3;
        $numberActors = 10;
        $actorCounter = 0;
        for ($i = 0; $i < $numberActors; $i++) {
            $actor = new Actor();
            //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
            $actor->setName($faker->name());

            for ($j = 0; $j <= $numberPrograms; $j++) {
                $actor->addProgram($this->getReference('program_' . $faker->numberBetween(1, 5)));
            }
            $actorCounter++;
            $this->addReference('actor_' . $actorCounter, $actor);

            $manager->persist($actor);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ProgramFixtures::class,
        ];
    }
}
