<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    #[Route('/discover', name: 'movie_discover')]
    public function index(
        CategoryRepository $categoryRepository,
        MovieRepository $movieRepository,
    ): Response {
        $categories = $categoryRepository->findAll();
        $recommendedMovies = $movieRepository->findRecommended(limit: 3); // Les 3 films les plus récents par exemple

        return $this->render('movie/discover.html.twig', [
            'categories' => $categories,
            'recommendedMovies' => $recommendedMovies
        ]);
    }
}
