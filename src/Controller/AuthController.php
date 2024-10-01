<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{

    #[Route('/login', name: 'auth.login')]
    public function login(): Response
    {
        return $this->render('auth/login.html.twig');
    }

    #[Route('/register', name: 'auth.register')]
    public function register(): Response
    {
        return $this->render('auth/register.html.twig');
    }

    #[Route('/confirm', name: 'auth.confirm')]
    public function confirm(): Response
    {
        return $this->render('auth/confirm.html.twig');
    }

    #[Route('/forgot', name: 'auth.forgot')]
    public function forgot(): Response
    {
        return $this->render('auth/forgot.html.twig');
    }

    #[Route('/reset', name: 'auth.reset')]
    public function reset(): Response
    {
        return $this->render('auth/reset.html.twig');
    }
}
