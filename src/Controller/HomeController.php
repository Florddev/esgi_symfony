<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\MovieRepository;
use App\Repository\SerieRepository;
use App\Repository\WatchHistoryRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route(path: '/', name: 'homepage')]
    public function index(
        MovieRepository $movieRepository,
        SerieRepository $serieRepository,
        WatchHistoryRepository $watchHistoryRepository
    ): Response {
        $latestMovies = $movieRepository->findBy([], ['releaseDate' => 'DESC'], 3);

        $latestSeries = $serieRepository->findBy([], ['releaseDate' => 'DESC'], 3);

        $watchHistory = [];
        if ($this->getUser()) {
            $watchHistory = $watchHistoryRepository->findByUser(
                $this->getUser(),
                limit: 3
            );
        }

        return $this->render('index.html.twig', [
            'latestMovies' => $latestMovies,
            'latestSeries' => $latestSeries,
            'watchHistory' => $watchHistory
        ]);
    }
}
