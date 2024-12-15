<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use App\Repository\PlaylistSubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserListController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route(path: '/lists', name: 'show_my_list')]
    public function show(
        Request $request,
        PlaylistRepository $playlistRepository,
        PlaylistSubscriptionRepository $playlistSubscriptionRepository,
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $playlistId = $request->query->get('playlist');

        $playlists = $playlistRepository->findBy(['creator' => $this->getUser()]);

        $subscribedPlaylists = $playlistSubscriptionRepository->findBy(['subscriber' => $this->getUser()]);

        // Récupérer la playlist active
        $activePlaylist = null;
        if ($playlistId) {
            $activePlaylist = $playlistRepository->find($playlistId);
        } elseif (count($playlists) > 0) {
            $activePlaylist = $playlists[0];
        } elseif (count($subscribedPlaylists) > 0) {
            $activePlaylist = $subscribedPlaylists[0]->getPlaylist();
        }

        return $this->render('other/lists.html.twig', [
            'playlists' => $playlists,
            'subscribedPlaylists' => $subscribedPlaylists,
            'activePlaylist' => $activePlaylist,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route(path: '/lists/create', name: 'create_playlist', methods: ['POST'])]
    public function createPlaylist(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $playlist = new Playlist();
        $playlist->setName($request->request->get('name'));
        $playlist->setCreator($this->getUser());
        $playlist->setCreatedAt(new \DateTimeImmutable());
        $playlist->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($playlist);
        $entityManager->flush();

        return $this->redirectToRoute('show_my_list', ['playlist' => $playlist->getId()]);
    }
}