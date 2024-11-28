<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function movies(): Response
    {
        return $this->render('admin/admin_films.html.twig');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/movies/add', name: 'admin_movies_add')]
    public function movies_add(): Response
    {
        return $this->render('admin/admin_add_films.html.twig');
    }
}
