<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UploadController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route(path: '/upload')]
    public function upload(): Response
    {
        return $this->render('other/upload.html.twig');
    }
}
