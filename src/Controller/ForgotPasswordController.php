<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Uuid;

class ForgotPasswordController {
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(
        Request $request,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('_email');
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                $resetToken = Uuid::v4();
                $user->setResetToken($resetToken);
                $em->flush();

                $email = (new TemplatedEmail())
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de mot de passe')
                    ->htmlTemplate('email/reset.html.twig')
                    ->context([
                        'resetToken' => $resetToken,
                        'userEmail' => $user->getEmail()
                    ]);

                $mailer->send($email);

                $this->addFlash('success', 'Email envoyé');
            }
        }

        return $this->render('auth/forgot.html.twig');
    }
}