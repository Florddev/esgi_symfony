<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Enum\UserAccountStatusEnum;
use App\Repository\MovieRepository;
use App\Repository\SerieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/admin')]
class AdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/', name: 'admin')]
    public function homepage(): Response
    {
        return $this->render('admin/homepage.html.twig');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/movies', name: 'admin_movies')]
    public function movies(
        Request $request,
        MovieRepository $movieRepository,
        SerieRepository $serieRepository
    ): Response {
        $search = $request->query->get('q');

        if ($search) {
            $movies = $movieRepository->findBySearch($search);
            $series = $serieRepository->findBySearch($search);
        } else {
            $movies = $movieRepository->findAll();
            $series = $serieRepository->findAll();
        }

        $medias = array_merge($movies, $series);

        return $this->render('admin/admin_films.html.twig', [
            'medias' => $medias,
            'search' => $search
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/movies/add', name: 'admin_movies_add')]
    public function movies_add(): Response
    {
        return $this->render('admin/admin_add_films.html.twig');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/users', name: 'admin_users')]
    public function users(
        Request $request,
        UserRepository $userRepository
    ): Response {
        $search = $request->query->get('q');

        if ($search) {
            $users = $userRepository->search($search);
        } else {
            $users = $userRepository->findAll();
        }

        return $this->render('admin/admin_users.html.twig', [
            'users' => $users,
            'search' => $search
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/users/{id}/toggle-status', name: 'admin_toggle_user_status')]
    public function toggleUserStatus(
        User $user,
        EntityManagerInterface $entityManager
    ): Response {
        // Toggle entre actif et bloqué
        if ($user->getAccountStatus() === UserAccountStatusEnum::ACTIVE) {
            $user->setAccountStatus(UserAccountStatusEnum::BANNED);
        } else {
            $user->setAccountStatus(UserAccountStatusEnum::ACTIVE);
        }

        $entityManager->flush();

        return $this->redirectToRoute('admin_users');
    }
}
