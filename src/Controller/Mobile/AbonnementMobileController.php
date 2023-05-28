<?php

namespace App\Controller\Mobile;

use App\Entity\Abonnement;
use App\Repository\AbonnementRepository;
use App\Repository\Offre2Repository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/abonnement")
 */
class AbonnementMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(AbonnementRepository $abonnementRepository): Response
    {
        $abonnements = $abonnementRepository->findAll();

        if ($abonnements) {
            return new JsonResponse($abonnements, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, Offre2Repository $Offre2Repository, UserRepository $userRepository): JsonResponse
    {
        $abonnement = new Abonnement();


        $offreAbonnement = $Offre2Repository->find((int)$request->get("offreAbonnement"));
        if (!$offreAbonnement) {
            return new JsonResponse("offreAbonnement with id " . (int)$request->get("offreAbonnement") . " does not exist", 203);
        }

        $user = $userRepository->find((int)$request->get("user"));
        if (!$user) {
            return new JsonResponse("user with id " . (int)$request->get("user") . " does not exist", 203);
        }


        $file = $request->files->get("file");
        if ($file) {
            $imageFileName = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move($this->getParameter('uploads_directory'), $imageFileName);
            } catch (FileException $e) {
                dd($e);
            }
        } else {
            if ($request->get("image")) {
                $imageFileName = $request->get("image");
            } else {
                $imageFileName = "null";
            }
        }

        $abonnement->constructor(
            $request->get("nom"),
            $request->get("prenom"),
            $imageFileName,
            $request->get("email"),
            $request->get("identifiant"),
            $request->get("cin"),
            $request->get("type"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateD")),
            DateTime::createFromFormat("d-m-Y", $request->get("dateF")),
            (int)$request->get("prix"),
            $offreAbonnement,
            $user
        );


        $email = $user->getEmailU();

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                $transport->setUsername('app.esprit.pidev@gmail.com')->setPassword('dqwqkdeyeffjnyif');
                $mailer = new Swift_Mailer($transport);
                $message = new Swift_Message('Notification');
                $message->setFrom(array('app.esprit.pidev@gmail.com' => 'Notification'))
                    ->setTo(array($email))
                    ->setBody("<h1>Abonnement ajouté avec succes</h1>", 'text/html');
                $mailer->send($message);
            } catch (Exception $exception) {
                return new JsonResponse(null, 405);
            }
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonnement);
        $entityManager->flush();

        return new JsonResponse($abonnement, 200);


    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, AbonnementRepository $abonnementRepository, Offre2Repository $Offre2Repository, UserRepository $userRepository): Response
    {
        $abonnement = $abonnementRepository->find((int)$request->get("id"));

        if (!$abonnement) {
            return new JsonResponse(null, 404);
        }


        $offreAbonnement = $Offre2Repository->find((int)$request->get("offreAbonnement"));
        if (!$offreAbonnement) {
            return new JsonResponse("offreAbonnement with id " . (int)$request->get("offreAbonnement") . " does not exist", 203);
        }

        $user = $userRepository->find((int)$request->get("user"));
        if (!$user) {
            return new JsonResponse("user with id " . (int)$request->get("user") . " does not exist", 203);
        }


        $file = $request->files->get("file");
        if ($file) {
            $imageFileName = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move($this->getParameter('uploads_directory'), $imageFileName);
            } catch (FileException $e) {
                dd($e);
            }
        } else {
            if ($request->get("image")) {
                $imageFileName = $request->get("image");
            } else {
                $imageFileName = "null";
            }
        }

        $abonnement->constructor(
            $request->get("nom"),
            $request->get("prenom"),
            $imageFileName,
            $request->get("email"),
            $request->get("identifiant"),
            $request->get("cin"),
            $request->get("type"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateD")),
            DateTime::createFromFormat("d-m-Y", $request->get("dateF")),
            (int)$request->get("prix"),
            $offreAbonnement,
            $user
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonnement);
        $entityManager->flush();

        return new JsonResponse($abonnement, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, AbonnementRepository $abonnementRepository): JsonResponse
    {
        $abonnement = $abonnementRepository->find((int)$request->get("id"));

        if (!$abonnement) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($abonnement);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }


    /**
     * @Route("/image/{image}", methods={"GET"})
     */
    public function getPicture(Request $request): BinaryFileResponse
    {
        return new BinaryFileResponse(
            $this->getParameter('uploads_directory') . "/" . $request->get("image")
        );
    }

}
