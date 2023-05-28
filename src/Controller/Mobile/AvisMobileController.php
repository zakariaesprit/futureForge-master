<?php
namespace App\Controller\Mobile;

use App\Entity\Avis;
use App\Repository\AvisRepository;
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
 * @Route("/mobile/avis")
 */
class AvisMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(AvisRepository $avisRepository): Response
    {
        $aviss = $avisRepository->findAll();

        if ($aviss) {
            return new JsonResponse($aviss, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, OffreRepository $OffreRepository, UserRepository $userRepository): JsonResponse
    {
        $avis = new Avis();

        
            $offreCovoiturage = $OffreRepository->find((int)$request->get("offreCovoiturage"));
        if (!$offreCovoiturage) {
            return new JsonResponse("offreCovoiturage with id " . (int)$request->get("offreCovoiturage") . " does not exist", 203);
        }
        
            $user = $userRepository->find((int)$request->get("user"));
        if (!$user) {
            return new JsonResponse("user with id " . (int)$request->get("user") . " does not exist", 203);
        }
        
        

        $avis->constructor(
            $offreCovoiturage,
            $user,
            (int)$request->get("rate"),
            $request->get("description")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($avis);
        $entityManager->flush();

        return new JsonResponse($avis, 200);

        
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, AvisRepository $avisRepository, OffreRepository $OffreRepository, UserRepository $userRepository): Response
    {
        $avis = $avisRepository->find((int)$request->get("id"));

        if (!$avis) {
            return new JsonResponse(null, 404);
        }

        
            $offreCovoiturage = $OffreRepository->find((int)$request->get("offreCovoiturage"));
        if (!$offreCovoiturage) {
        return new JsonResponse("offreCovoiturage with id " . (int)$request->get("offreCovoiturage") . " does not exist", 203);
        }
        
            $user = $userRepository->find((int)$request->get("user"));
        if (!$user) {
        return new JsonResponse("user with id " . (int)$request->get("user") . " does not exist", 203);
        }
        
        

        $avis->constructor(
            $offreCovoiturage,
            $user,
            (int)$request->get("rate"),
            $request->get("description")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($avis);
        $entityManager->flush();

        return new JsonResponse($avis, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, AvisRepository $avisRepository): JsonResponse
    {
        $avis = $avisRepository->find((int)$request->get("id"));

        if (!$avis) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($avis);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    
}
