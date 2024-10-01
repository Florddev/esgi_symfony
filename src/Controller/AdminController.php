<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin.index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/films', name: 'admin.films')]
    public function films(): Response
    {
        return $this->render('admin/films.html.twig');
    }

    #[Route('/admin/users', name: 'admin.users')]
    public function users(): Response
    {
        return $this->render('admin/users.html.twig');
    }

    #[Route('/admin/add_films', name: 'admin.add_films')]
    public function add_films(): Response
    {
        return $this->render('admin/add_films.html.twig');
    }

    #[Route('/admin/upload', name: 'admin.upload')]
    public function upload(): Response
    {
        return $this->render('admin/upload.html.twig');
    }
}
