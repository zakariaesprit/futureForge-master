<?php

namespace App\Controller\Mobile;

use App\Entity\Offre;
use App\Repository\OffreRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/offreCovoiturage")
 */
class OffreCovoiturageMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(OffreRepository $OffreRepository): Response
    {
        $offreCovoiturages = $OffreRepository->findAll();

        if ($offreCovoiturages) {
            return new JsonResponse($offreCovoiturages, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, UserRepository $userRepository): JsonResponse
    {
        $offreCovoiturage = new Offre();


        $user = $userRepository->find(1);
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

        $offreCovoiturage->constructor(
            $user,
            $imageFileName,
            $request->get("prenomChauff"),
            $request->get("numChauff"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateOffre")),
            DateTime::createFromFormat("H:i", $request->get("heure")),
            (int)$request->get("prixOffre"),
            $request->get("depart"),
            $request->get("destination"),
            (int)$request->get("placesDispo")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offreCovoiturage);
        $entityManager->flush();

        return new JsonResponse($offreCovoiturage, 200);


    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, OffreRepository $OffreRepository, UserRepository $userRepository): Response
    {
        $offreCovoiturage = $OffreRepository->find((int)$request->get("id"));

        if (!$offreCovoiturage) {
            return new JsonResponse(null, 404);
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

        $offreCovoiturage->constructor(
            $user,
            $imageFileName,
            $request->get("prenomChauff"),
            $request->get("numChauff"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateOffre")),
            DateTime::createFromFormat("H:i", $request->get("heure")),
            (int)$request->get("prixOffre"),
            $request->get("depart"),
            $request->get("destination"),
            (int)$request->get("placesDispo")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offreCovoiturage);
        $entityManager->flush();

        return new JsonResponse($offreCovoiturage, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, OffreRepository $OffreRepository): JsonResponse
    {
        $offreCovoiturage = $OffreRepository->find((int)$request->get("id"));

        if (!$offreCovoiturage) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($offreCovoiturage);
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
