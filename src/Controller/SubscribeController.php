<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscribeController extends AbstractController
{
    #[Route('/subscribe', name: 'subscribe.index')]
    public function index(): Response
    {
        return $this->render('subscribe/index.html.twig');
    }
}
