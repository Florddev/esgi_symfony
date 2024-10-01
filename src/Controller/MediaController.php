<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MediaController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/media/default', name: 'media.default')]
    public function default(): Response
    {
        return $this->render('media/default.html.twig');
    }

    #[Route('/media/detail', name: 'media.detail')]
    public function detail(): Response
    {
        return $this->render('media/detail.html.twig');
    }

    #[Route('/media/lists', name: 'media.lists')]
    public function lists(): Response
    {
        return $this->render('media/lists.html.twig');
    }

    #[Route('/media/discover', name: 'media.discover')]
    public function discover(): Response
    {
        return $this->render('media/discover.html.twig');
    }

    #[Route('/media/detail_serie', name: 'media.detail_serie')]
    public function detail_serie(): Response
    {
        return $this->render('media/detail_serie.html.twig');
    }

    #[Route('/media/category', name: 'media.category')]
    public function category(): Response
    {
        return $this->render('media/category.html.twig');
    }
}
