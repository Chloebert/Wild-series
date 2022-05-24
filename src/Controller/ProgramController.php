<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use doctrine\persistence\ManagerRegisty;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;

class ProgramController extends AbstractController
{
    #[Route('/program/', name: 'program_index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
         ]);
    }

    #[Route('/program/{id}', requirements: ['id'=>'\d+'], methods: ['GET'], name: 'program_show')]
    public function show(int $id, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException('No program with id : '.$id. ' found in program\'s table.');
        };

        $seasons = $seasonRepository->findBy(['program' => $program]);

        return $this->render('program/show.html.twig', ['program' => $program, 'seasons' => $seasons]);
    }
    
    #[Route('/program/{programId}/season/{seasonId}', requirements: ['id'=>'\d+'], methods: ['GET'], name: 'program_season_show')]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $programId]);

        if (!$program) {
            throw $this->createNotFoundException('No program with id : '.$programId. ' found in program\'s table.');
        };

        $season = $seasonRepository->findOneById([$seasonId]);

        $episodes = $episodeRepository->findBy(['season' => $season]);

        return $this->render('program/season_show.html.twig', ['program' => $program, 'season' => $season, 'episodes' => $episodes]);
    }
}