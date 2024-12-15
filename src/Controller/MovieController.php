<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Movie;
use App\Enum\CommentStatusEnum;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MovieController extends AbstractController
{
    #[Route('/movie/{id}', name: 'show_movie')]
    public function movie(
        Movie $movie,
        CommentRepository $commentRepository
    ): Response {
        $validatedComments = $commentRepository->findValidatedByMedia($movie);

        return $this->render('movie/detail.html.twig', [
            'movie' => $movie,
            'comments' => $validatedComments
        ]);
    }

    #[Route('/serie', name: 'show_serie')]
    public function series(): Response
    {
        return $this->render('movie/detail_serie.html.twig');
    }

    // MovieController.php
    #[Route('/movie/{id}/comment', name: 'add_comment', methods: ['POST'])]
    public function addComment(
        Request $request,
        Movie $movie,
        EntityManagerInterface $em
    ): Response {
        if (!$this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $content = $request->request->get('content');

        $comment = new Comment();
        $comment->setContent($content);
        $comment->setMedia($movie);
        $comment->setPublisher($this->getUser());
        $comment->setStatus(CommentStatusEnum::WAITING);

        $em->persist($comment);
        $em->flush();

        $this->addFlash('success', 'Votre commentaire a été ajouté et sera visible après modération');

        return $this->redirectToRoute('show_movie', ['id' => $movie->getId()]);
    }
}
