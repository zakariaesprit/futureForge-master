<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request,AuthenticationUtils $authenticationUtils, MailerInterface $mailer): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(MailerInterface $mailer): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/profile', name: 'profile')]
    public function profile(Request $request, AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        
        $user = $this->getUser(); // get the authenticated user
        $form = $this->createForm(UserType::class, $user); // create a form based on the UserType form type, and pre-populate it with the current user's data
        //mail
        $email = (new Email())
            ->from('brakajgames@gmail.com')
            ->to('zakariaayachi1@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Welcome to thniyti !')
            ->text('Someone logged in to you account!')
            ->html('<p>Someone logged in to you account!</p>');

        $mailer->send($email);
        //mail
        // handle the form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // persist the updated user entity
            $entityManager->persist($user);
            $entityManager->flush();
            // redirect to the profile page
            return $this->redirectToRoute('profile');
        }
        // render the profile page with the form
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        if(!($user->isActive()))
        {
            $day = $user->getBlockDate()->format('d');
            $month = $user->getBlockDate()->format('m');
            $year = $user->getBlockDate()->format('y');
            $hour = $user->getBlockDate()->format('h');
            $min = $user->getBlockDate()->format('m');
            $sec = $user->getBlockDate()->format('s');
            return $this->render('security/forbidden.html.twig', [
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'hour' => $hour,
                'min' => $min,
                'sec' => $sec,
            ]);
        
    }
    else
    {return $this->render('user/profile.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
        'form' => $form->createView(), // pass the form view to the template
    ]);
        
        
    }
    }

}
