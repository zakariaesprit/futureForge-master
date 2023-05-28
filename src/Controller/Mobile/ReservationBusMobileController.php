<?php
namespace App\Controller\Mobile;

use App\Entity\ReservationBus;
use App\Repository\ReservationBusRepository;
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
 * @Route("/mobile/reservationBus")
 */
class ReservationBusMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(ReservationBusRepository $reservationBusRepository): Response
    {
        $reservationBuss = $reservationBusRepository->findAll();

        if ($reservationBuss) {
            return new JsonResponse($reservationBuss, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $reservationBus = new ReservationBus();

        
        

        $reservationBus->constructor(
            $request->get("nom"),
            $request->get("prenom"),
            (int)$request->get("numPlace"),
            DateTime::createFromFormat("d-m-Y", $request->get("date")),
            $request->get("email"),
            $request->get("destination")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservationBus);
        $entityManager->flush();

        return new JsonResponse($reservationBus, 200);

        
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, ReservationBusRepository $reservationBusRepository): Response
    {
        $reservationBus = $reservationBusRepository->find((int)$request->get("id"));

        if (!$reservationBus) {
            return new JsonResponse(null, 404);
        }

        
        

        $reservationBus->constructor(
            $request->get("nom"),
            $request->get("prenom"),
            (int)$request->get("numPlace"),
            DateTime::createFromFormat("d-m-Y", $request->get("date")),
            $request->get("email"),
            $request->get("destination")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservationBus);
        $entityManager->flush();

        return new JsonResponse($reservationBus, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ReservationBusRepository $reservationBusRepository): JsonResponse
    {
        $reservationBus = $reservationBusRepository->find((int)$request->get("id"));

        if (!$reservationBus) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($reservationBus);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    
}
