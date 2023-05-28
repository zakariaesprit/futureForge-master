<?php
namespace App\Controller\Mobile;

use App\Entity\ReservationCovoiturage;
use App\Repository\ReservationCovoiturageRepository;
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
 * @Route("/mobile/reservationCovoiturage")
 */
class ReservationCovoiturageMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(ReservationCovoiturageRepository $reservationCovoiturageRepository): Response
    {
        $reservationCovoiturages = $reservationCovoiturageRepository->findAll();

        if ($reservationCovoiturages) {
            return new JsonResponse($reservationCovoiturages, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $reservationCovoiturage = new ReservationCovoiturage();

        
        

        $reservationCovoiturage->constructor(
            $request->get("nom"),
            $request->get("prenom"),
            $request->get("pntRencontre"),
            $request->get("distination"),
            (int)$request->get("nbrPlace"),
            DateTime::createFromFormat("d-m-Y", $request->get("date"))
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservationCovoiturage);
        $entityManager->flush();

        return new JsonResponse($reservationCovoiturage, 200);

        
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, ReservationCovoiturageRepository $reservationCovoiturageRepository): Response
    {
        $reservationCovoiturage = $reservationCovoiturageRepository->find((int)$request->get("id"));

        if (!$reservationCovoiturage) {
            return new JsonResponse(null, 404);
        }

        
        

        $reservationCovoiturage->constructor(
            $request->get("nom"),
            $request->get("prenom"),
            $request->get("pntRencontre"),
            $request->get("distination"),
            (int)$request->get("nbrPlace"),
            DateTime::createFromFormat("d-m-Y", $request->get("date"))
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservationCovoiturage);
        $entityManager->flush();

        return new JsonResponse($reservationCovoiturage, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ReservationCovoiturageRepository $reservationCovoiturageRepository): JsonResponse
    {
        $reservationCovoiturage = $reservationCovoiturageRepository->find((int)$request->get("id"));

        if (!$reservationCovoiturage) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($reservationCovoiturage);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    
}
