<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


#[Route('/users')]
class UserController extends AbstractController
{

    private $passwordEncoder;

    
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, TranslatorInterface $translator): Response
    {
        $translated = $translator->trans('welcome');
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route('/mobile', name: 'app_user_mobile', methods: ['GET', 'POST'])]
    public function mobile(UserRepository $userRepository, TranslatorInterface $translator): JsonResponse
    {
    $translated = $translator->trans('welcome');
    $users = $userRepository->findAll();
    $datas = array();
    foreach ($users as $key => $user)
    {
        $datas[$key]['id'] = $user->getId();
        $datas[$key]['nom'] = $user->getNom();
        $datas[$key]['prenom'] = $user->getPrenom();
        $datas[$key]['email'] = $user->getEmail();
        $datas[$key]['password'] = $user->getPassword();
        $datas[$key]['username'] = $user->getUsername();
        $datas[$key]['matricule'] = $user->getMatricule();
        $datas[$key]['pfp'] = $user->getPfpU();
        $datas[$key]['bio'] = $user->getBio();
        $datas[$key]['active'] = $user->isActive();
        $datas[$key]['blockdate'] = $user->getBlockDate();
    }

    return new JsonResponse([
        'users' => $datas,
    ]);
    }
    #[Route('/mobile/add', name: 'app_user_mobile_add')]
    public function mobile_add(Request $request)
    {
        $email = $request->query->get("email");
        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $password = $request->query->get("password");
        $username = $request->query->get("username");
        $roles = $request->query->get("roles");
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return new Response ("email invalid");
        }
        $user = new User();
        $user->setEmail($email);
        $user->setRoles(array($roles));
        $user->setPassword($password);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setUsername($username);
        try{
            $em  =$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $randomNumber = rand(1000, 9999);
            $email = (new Email())
        ->from('brakajgames@gmail.com')
        ->to($email)
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Welcome to thniyti From Mobile!')
        ->text('Someone logged in to you account from a phone!')
        ->html('<html>
                <head>
                    <style>
                        /* Customize your email styles here */
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f2f2f2;
                            padding: 20px;
                        }
                        
                        h1 {
                            color: #363636;
                            text-align: center;
                        }
                        h2 {
                            color: red;
                            text-align: center;
                        }
                        
                        .content {
                            background-color: #ffffff;
                            border-radius: 5px;
                            padding: 20px;
                            margin-top: 20px;
                        }
                        
                        p {
                            font-size: 16px;
                            line-height: 1.5em;
                        }
                        
                        .button {
                            display: inline-block;
                            background-color: #4CAF50;
                            color: #ffffff;
                            padding: 10px 20px;
                            border-radius: 5px;
                            text-decoration: none;
                        }
                        
                        .button:hover {
                            background-color: #3e8e41;
                        }
                    </style>
                </head>
                <body>
                    <h1>Welcome to thniyti!</h1>
                    <div class="content">
                        <p>Dear '.$user->getUsername().',</p>
                        <p>Thank you for choosing our service!</p>
                        <p>heres your confirmation code.</p>
                        <a class="button" href="#">'.$randomNumber.'</a>
                        <div class="image-wrapper">
                        <img src="{{ asset("front/images/Untitled-1.png") }}" alt="Logo">
                    </div>
                    <div class="footer">
                        This email was sent from Thniyti. &copy; 2023 All rights reserved.
                    </div>
                </body>
            </html>');

    $mailer->send($email);
    //mail

            return new JsonResponse ("Accoutn is created",200);
        }
        catch (Exception $ex){
            return new Response ("exception".ex->getMessage());
    }

    }
    #[Route('/mobile/login', name: 'app_user_mobile_login')]
    public function mobile_login(Request $request, MailerInterface $mailer)
    {
        $email = $request->query->get("email");
        $password = $request->query->get("password");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email'=>$email]);
        if($user)
        {
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                //mail
        $email = (new Email())
        ->from('brakajgames@gmail.com')
        ->to($email)
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Welcome to thniyti From Mobile!')
        ->text('Someone logged in to you account from a phone!')
        ->html('<html>
                <head>
                    <style>
                        /* Customize your email styles here */
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f2f2f2;
                            padding: 20px;
                        }
                        
                        h1 {
                            color: #363636;
                            text-align: center;
                        }
                        h2 {
                            color: red;
                            text-align: center;
                        }
                        
                        .content {
                            background-color: #ffffff;
                            border-radius: 5px;
                            padding: 20px;
                            margin-top: 20px;
                        }
                        
                        p {
                            font-size: 16px;
                            line-height: 1.5em;
                        }
                        
                        .button {
                            display: inline-block;
                            background-color: #4CAF50;
                            color: #ffffff;
                            padding: 10px 20px;
                            border-radius: 5px;
                            text-decoration: none;
                        }
                        
                        .button:hover {
                            background-color: #3e8e41;
                        }
                    </style>
                </head>
                <body>
                    <h1>Security message!</h1>
                    <div class="content">
                        <h2>Dear '.$user->getUsername().',</h2>
                        <h2>Someone logged to you account from a phone!</h2>
                    <div class="footer">
                        This email was sent from Thniyti. &copy; 2023 All rights reserved.
                    </div>
                </body>
            </html>');

    $mailer->send($email);
    //mail
                return new JsonResponse($formatted);
                
        }
        else{
            return new JsonResponse("User not found");
        }
    }

    #[Route('/profile', name: 'app_user_profile', methods: ['GET'])]
    public function profile(#[ParamConverter('user', class: 'App\Entity\User')] User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        return new Response('Well hi there '.$user->getFirstName());
    }

    #[Route('/back', name: 'app_user_back', methods: ['GET'])]
    public function back(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pfp = $form->get('pfp_u')->getData();
            if ($pfp) {
                $originalFilename = pathinfo($pfp->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pfp->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $pfp->move(
                        $this->getParameter('user_pfp'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setPfpU($newFilename);
            }
            //password
            $plaintextPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setActive(true);
            $user->setPassword($hashedPassword);
            //password
            $randomNumber = rand(1000, 9999);
            $user->setVerifCode($randomNumber);
            $userRepository->save($user, true);
            //mail
        //sms
        
        //mail
        $email = (new Email())
            ->from('brakajgames@gmail.com')
            ->to($user->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Welcome to thniyti !')
            ->text('Someone logged in to you account!')
            ->html('<html>
                <head>
                    <style>
                        /* Customize your email styles here */
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f2f2f2;
                            padding: 20px;
                        }
                        
                        h1 {
                            color: #363636;
                            text-align: center;
                        }
                        
                        .content {
                            background-color: #ffffff;
                            border-radius: 5px;
                            padding: 20px;
                            margin-top: 20px;
                        }
                        
                        p {
                            font-size: 16px;
                            line-height: 1.5em;
                        }
                        
                        .button {
                            display: inline-block;
                            background-color: #4CAF50;
                            color: #ffffff;
                            padding: 10px 20px;
                            border-radius: 5px;
                            text-decoration: none;
                        }
                        
                        .button:hover {
                            background-color: #3e8e41;
                        }
                    </style>
                </head>
                <body>
                    <h1>Welcome to thniyti!</h1>
                    <div class="content">
                        <p>Dear '.$user->getUsername().',</p>
                        <p>Thank you for choosing our service!</p>
                        <p>heres your confirmation code.</p>
                        <a class="button" href="#">'.$randomNumber.'</a>
                        <div class="image-wrapper">
                        <img src="{{ asset("front/images/Untitled-1.png") }}" alt="Logo">
                    </div>
                    <div class="footer">
                        This email was sent from Thniyti. &copy; 2023 All rights reserved.
                    </div>
                </body>
            </html>');

        $mailer->send($email);
        //mail
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            
            

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    public function searchUser(Request $request, UserRepository $userRepository)
    {
        $form = $this->createForm(SearchUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            $users = $userRepository->search($query);
            // render the search results
            return $this->render('user/search.html.twig', [
                'users' => $users,
            ]);
        }

        // render the search form
        return $this->render('user/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
