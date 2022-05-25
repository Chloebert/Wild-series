<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Episode;
use Faker\Factory;
use App\Entity\Season;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        /**
         * L'objet $faker que tu récupère est l'outil qui va te permettre 
         * de te générer toutes les données que tu souhaites
         */
        $numberPrograms = 5;
        $numberSeasons = 8;
        for ($a = 1; $a <= $numberPrograms; $a++) {
            for ($i = 1; $i <= $numberSeasons; $i++) {
                $numberEpisodes = 15;
                for ($j = 1; $j < $numberEpisodes; $j++) {
                    $episode = new Episode();
                    $episode->setNumber($j);
                    $episode->setTitle($faker->words(3, true));
                    $episode->setSynopsis($faker->paragraphs(2, true));

                    $episode->setSeason($this->getReference('season_' . $i . '-' . $a));

                    $manager->persist($episode);
                }

                $manager->flush();
            }
        }
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            SeasonFixtures::class,
        ];
    }
}
