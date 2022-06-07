<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use doctrine\persistence\ManagerRegisty;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\CommentRepository;
use App\Form\ProgramType;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Comment;
use App\Service\Slugify;
use App\Form\CommentType;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, MailerInterface $mailer, programRepository $programRepository, Slugify $slugify): Response
    {
        $program = new Program();

        // Create the form, linked with $category
        $form = $this->createForm(ProgramType::class, $program);

        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $programRepository->add($program, true);

            $email = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to('your_email@example.com')
            ->subject('Une nouvelle série vient d\'être publiée !')
            ->html($this->renderView('Program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);

            // Redirect to categories list
            return $this->redirectToRoute('program_index');
        }

        // Render the form (best practice)
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', methods: ['GET'], name: 'show')]
    public function show(Program $program, SeasonRepository $seasonRepository): Response
    {

        if (!$program) {
            throw $this->createNotFoundException('No program with id : ' . $program . ' found in program\'s table.');
        };

        $seasons = $seasonRepository->findBy(['program' => $program]);

        return $this->render('program/show.html.twig', ['program' => $program, 'seasons' => $seasons]);
    }

    #[Route('/{slug}/season/{season}', requirements: ['season' => '\d+'], methods: ['GET'], name: 'season_show')]
    public function showSeason(Program $program, Season $season, EpisodeRepository $episodeRepository): Response
    {
        if (!$program) {
            throw $this->createNotFoundException('No program with id : ' . $program->getId() . ' found in program\'s table.');
        };

        $episodes = $episodeRepository->findBy(['season' => $season]);

        return $this->render('program/season_show.html.twig', ['program' => $program, 'season' => $season, 'episodes' => $episodes]);
    }

    #[Route('/{slug}/season/{season}/episode/{episodeSlug}', requirements: ['season' => '\d+'], name: 'episode_show')]
    #[ParamConverter('program', options: ['mapping' => ['slug' => 'slug']])]
    #[ParamConverter('episode', options: ['mapping' => ['episodeSlug' => 'slug']])]
    public function showEpisode(Program $program, Season $season, Episode $episode, CommentRepository $commentRepository, Request $request)
    {

        if (!$episode) {
            throw $this->createNotFoundException('No episode with id : ' . $episode->getId() . ' found in program\'s table.');
        };

        $user = $this->getUser();

        $comment = new Comment();

        // Create the form, linked with $category
        $form = $this->createForm(CommentType::class, $comment);

        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($user);
            $comment->setEpisodeId($episode);
            $commentRepository->add($comment, true);

            return $this->redirectToRoute('program_episode_show', [
                'slug' => $program->getSlug(),
                'season' => $season->getId(),
                'episodeSlug' => $episode->getSlug()
            ]);
        }

        return $this->render(
            'program/episode_show.html.twig',
            ['program' => $program, 'season' => $season, 'episode' => $episode, 'form' => $form->createView()]
        );
    }
}
