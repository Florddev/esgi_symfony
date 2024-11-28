<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ResetPasswordController
{
    #[Route('/reset-password', name: 'app_reset_password')]
    public function resetPassword(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response
    {
        $token = $request->get('token');
        $user = $em->getRepository(User::class)->findOneBy(['resetToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Token invalide');
        }

        if ($request->isMethod('POST')) {
            $password = $request->get('password');
            $confirm = $request->get('confirm');

            if ($password === $confirm) {
                $hashedPassword = $hasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
                $user->setResetToken(null);
                $em->flush();

                $this->addFlash('success', 'Mot de passe modifié');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/reset.html.twig', [
            'token' => $token
        ]);
    }
}