<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ResetPasswordToken;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    #[Route('', name: 'app_forgot_password_request')]
    public function request(Request $request): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                $resetToken = Uuid::v4()->toRfc4122();
                $expiresAt = new \DateTime('+1 hour');

                $resetPasswordToken = new ResetPasswordToken();
                $resetPasswordToken->setUser($user);
                $resetPasswordToken->setToken($resetToken);
                $resetPasswordToken->setExpiresAt($expiresAt);

                $this->entityManager->persist($resetPasswordToken);
                $this->entityManager->flush();

                $email = (new TemplatedEmail())
                    ->from(new Address('no-reply@yourdomain.com', 'Your App Name'))
                    ->to($user->getEmail())
                    ->subject('Your password reset request')
                    ->htmlTemplate('reset_password/email.html.twig')
                    ->context(['resetToken' => $resetToken]);

                $this->mailer->send($email);
            }

            return $this->redirectToRoute('app_check_email');
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    #[Route('/check-email', name: 'app_check_email')]
    public function checkEmail(): Response
    {
        // Peut être utilisé pour confirmer à l'utilisateur qu'un email a été envoyé s'il existe, sans révéler l'existence de l'utilisateur
        return $this->render('reset_password/check_email.html.twig');
    }

    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, string $token = null): Response
    {
        if (!$token) {
            return $this->redirectToRoute('app_forgot_password_request');
        }

        $resetPasswordToken = $this->entityManager->getRepository(ResetPasswordToken::class)->findOneBy(['token' => $token, 'used' => false]);

        if (!$resetPasswordToken || $resetPasswordToken->isExpired()) {
            $this->addFlash('reset_password_error', 'The password reset token is invalid or has expired.');
            return $this->redirectToRoute('app_forgot_password_request');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $resetPasswordToken->getUser();
            $encodedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());

            $user->setPassword($encodedPassword);
            $resetPasswordToken->setUsed(true);

            $this->entityManager->flush();

            $this->addFlash('notice', 'Your password has been reset successfully.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
