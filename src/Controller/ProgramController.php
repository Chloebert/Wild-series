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
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;

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

    #[Route('/{program}', requirements: ['program'=>'\d+'], methods: ['GET'], name: 'program_show')]
    public function show(Program $program, SeasonRepository $seasonRepository): Response
    {

        if (!$program) {
            throw $this->createNotFoundException('No program with id : '.$program. ' found in program\'s table.');
        };

        $seasons = $seasonRepository->findBy(['program' => $program]);

        return $this->render('program/show.html.twig', ['program' => $program, 'seasons' => $seasons]);
    }
    
    #[Route('/{program}/season/{season}', requirements: ['program'=>'\d+', 'season'=>'\d+'], methods: ['GET'], name: 'program_season_show')]
    public function showSeason(Program $program, Season $season, EpisodeRepository $episodeRepository): Response
    {
        if (!$program) {
            throw $this->createNotFoundException('No program with id : '.$program. ' found in program\'s table.');
        };

        $episodes = $episodeRepository->findBy(['season' => $season]);

        return $this->render('program/season_show.html.twig', ['program' => $program, 'season' => $season, 'episodes' => $episodes]);
    }

    #[Route('/{program}/season/{season}/episode/{episode}', requirements: ['program'=>'\d+', 'season'=>'\d+', 'episode'=>'\d+'], methods: ['GET'], name: 'program_episode_show')]
    public function showEpisode(Program $program, Season $season, Episode $episode) {

        if (!$episode) {
            throw $this->createNotFoundException('No episode with id : '.$episode. ' found in program\'s table.');
        };

        return $this->render('program/episode_show.html.twig', 
        ['program' => $program, 'season' => $season, 'episode' => $episode]);

    }
}