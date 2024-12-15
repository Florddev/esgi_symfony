<?php
namespace App\Controller;

use App\Entity\Media;
use App\Entity\WatchHistory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WatchController extends AbstractController
{
    #[Route('/watch/{id}', name: 'watch_media')]
    public function watch(
        Media $media,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $history = $entityManager->getRepository(WatchHistory::class)->findOneBy([
            'watcher' => $this->getUser(),
            'media' => $media
        ]);

        if (!$history) {
            $history = new WatchHistory();
            $history->setWatcher($this->getUser());
            $history->setMedia($media);
            $history->setNumberOfViews(0);
        }

        $history->setLastWatchedAt(new \DateTimeImmutable());
        $history->setNumberOfViews($history->getNumberOfViews() + 1);

        $entityManager->persist($history);
        $entityManager->flush();

        return $this->render('watch/player.html.twig', [
            'media' => $media,
            'history' => $history
        ]);
    }
}