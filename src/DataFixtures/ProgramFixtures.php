<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $program = new Program();
        $program->setTitle('The Haunting of Hill House');
        $program->setSynopsis('Plusieurs frères et sœurs qui, enfants, ont grandi dans la demeure qui allait devenir la maison hantée la plus célèbre des États-Unis sont contraints de se retrouver pour faire face à cette tragédie ensemble. La famille doit enfin affronter les fantômes de son passé, dont certains sont encore bien présents dans leurs esprits alors que d’autres continuent de traquer Hill House.');
        $program->setCategory($this->getReference('category_Horreur'));
        $manager->persist($program);

        $program2 = new Program();
        $program2->setTitle('Walking Dead');
        $program2->setSynopsis('Le policier Rick Grimes se réveille après un long coma. Il découvre avec effarement que le monde, ravagé par une épidémie, est envahi par les morts-vivants.');
        $program2->setCategory($this->getReference('category_Action'));
        $manager->persist($program2);

        $program3 = new Program();
        $program3->setTitle('Teen Wolf');
        $program3->setSynopsis('Adolescent ordinaire, Scott McCall voit sa vie bouleversée suite à une morsure de loup-garou. Pas facile de maîtriser ses nouveaux instincts, d\'autant que ses hormones sont mises à l\'épreuve avec l\'arrivée d\'une nouvelle élève qui ne le laisse pas insensible. Heureusement, son ami Stiles veille.');
        $program3->setCategory($this->getReference('category_Fantastique'));
        $manager->persist($program3);

        $program4 = new Program();
        $program4->setTitle('Naruto');
        $program4->setSynopsis('Dans le village de Konoha vit Naruto, un jeune garçon détesté et craint des villageois, du fait qu\'il détient en lui Kyuubi (démon renard à neuf queues) d\'une incroyable force. Naruto rêve de devenir le plus grand Hokage de Konoha afin que tous le reconnaissent à sa juste valeur. Mais la route pour devenir Hokage est très longue.');
        $program4->setCategory($this->getReference('category_Animation'));
        $manager->persist($program4);

        $program5 = new Program();
        $program5->setTitle('Bleach');
        $program5->setSynopsis('Ichigo, un ado de 15 ans ayant la faculté de communiquer avec les âmes errantes, se voit assigner la mission d\'affronter les \"Hollows\", esprits monstrueux qui se nourissent des humains et des fantômes...');
        $program5->setCategory($this->getReference('category_Animation'));
        $manager->persist($program5);

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
        ];
    }

}
