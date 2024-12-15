<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\SubscriptionHistory;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SubscriptionController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route(path: '/subscriptions', name: 'subscriptions')]
    public function show(
        SubscriptionRepository $subscriptionRepository,
        SubscriptionHistoryRepository $subscriptionHistoryRepository
    ): Response {
        $subscriptions = $subscriptionRepository->findAll();

        $currentSubscription = null;
        if ($this->getUser()) {
            $currentSubscription = $this->getUser()->getCurrentSubscription();
        }

        return $this->render('other/abonnements.html.twig', [
            'subscriptions' => $subscriptions,
            'currentSubscription' => $currentSubscription,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route(path: '/subscription/{id}/subscribe', name: 'subscribe_to_plan')]
    public function subscribe(
        Subscription $subscription,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $user->setCurrentSubscription($subscription);

        // Créer l'historique
        $history = new SubscriptionHistory();
        $history->setSubscriber($user);
        $history->setSubscription($subscription);
        $history->setStartAt(new \DateTimeImmutable());
        $history->setEndAt((new \DateTimeImmutable())->modify(sprintf('+%d months', $subscription->getDuration())));

        $entityManager->persist($history);
        $entityManager->flush();

        $this->addFlash('success', 'Votre abonnement a bien été souscrit');
        return $this->redirectToRoute('subscriptions');
    }
}
