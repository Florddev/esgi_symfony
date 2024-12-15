<?php
namespace App\Controller;

use App\Repository\MovieRepository;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function search(
        Request $request,
        MovieRepository $movieRepository,
        SerieRepository $serieRepository
    ): Response {
        $query = $request->query->get('q');
        $type = $request->query->get('type', 'all'); // all, movie, serie

        $results = [];

        if ($type === 'all' || $type === 'movie') {
            $movies = $movieRepository->search($query);
            $results['movies'] = $movies;
        }

        if ($type === 'all' || $type === 'serie') {
            $series = $serieRepository->search($query);
            $results['series'] = $series;
        }

        return $this->render('search/results.html.twig', [
            'query' => $query,
            'type' => $type,
            'results' => $results
        ]);
    }
}