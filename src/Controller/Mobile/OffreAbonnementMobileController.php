<?php
namespace App\Controller\Mobile;

use App\Entity\Offre2;
use App\Repository\Offre2Repository;
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
 * @Route("/mobile/offreAbonnement")
 */
class OffreAbonnementMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(Offre2Repository $Offre2Repository): Response
    {
        $offreAbonnements = $Offre2Repository->findAll();

        if ($offreAbonnements) {
            return new JsonResponse($offreAbonnements, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $offreAbonnement = new Offre2();

        
        

        $offreAbonnement->constructor(
            $request->get("nom"),
            $request->get("description"),
            (int)$request->get("reduction"),
            $request->get("type"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateD")),
            DateTime::createFromFormat("d-m-Y", $request->get("dateF"))
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offreAbonnement);
        $entityManager->flush();

        return new JsonResponse($offreAbonnement, 200);

        
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, Offre2Repository $Offre2Repository): Response
    {
        $offreAbonnement = $Offre2Repository->find((int)$request->get("id"));

        if (!$offreAbonnement) {
            return new JsonResponse(null, 404);
        }

        
        

        $offreAbonnement->constructor(
            $request->get("nom"),
            $request->get("description"),
            (int)$request->get("reduction"),
            $request->get("type"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateD")),
            DateTime::createFromFormat("d-m-Y", $request->get("dateF"))
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offreAbonnement);
        $entityManager->flush();

        return new JsonResponse($offreAbonnement, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, Offre2Repository $Offre2Repository): JsonResponse
    {
        $offreAbonnement = $Offre2Repository->find((int)$request->get("id"));

        if (!$offreAbonnement) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($offreAbonnement);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    
}
