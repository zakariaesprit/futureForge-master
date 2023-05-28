<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/admin/offre')]
class OffreAdminController extends AbstractController
{
    private $offreRepository;

    public function __construct(OffreRepository $offreRepository)
    {
        $this->offreRepository = $offreRepository;
    }

    #[Route('/', name: 'admin_offre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $offres = $entityManager
            ->getRepository(Offre::class)
            ->findAll();

        return $this->render('offreAdmin/index.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/{idOffre}', name: 'admin_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offreAdmin/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{idOffre}', name: 'admin_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getIdOffre(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_offre_index', [], Response::HTTP_SEE_OTHER);
    }

    public function searchOffre(Request $request)
    {
        $searchTerm = $request->query->get('searchTerm');

        if ($searchTerm === '') {
            $offres = $this->offreRepository->findAll();
        } else {
            $offres = $this->offreRepository->findByNom($searchTerm);
        }

        $response = [];
        foreach ($offres as $offre) {
            $response[] = [
                'imageVehicule' => $offre->getImageVehicule(),
                'prenomChauff' => $offre->getPrenomChauff(),
                'numChauff' => $offre->getNumChauff(),
                'dateOffre' => $offre->getDateOffre() ? $offre->getDateOffre()->format('Y-m-d') : null,
                'heure' => $offre->getHeure() ? $offre->getHeure()->format('H:i:s') : null,
                'prixOffre' => $offre->getPrixOffre(),
                'depart' => $offre->getDepart(),
                'destination' => $offre->getDestination(),
                'placesDispo' => $offre->getPlacesDispo(),
                'showUrl' => $this->generateUrl('admin_offre_show', ['idOffre' => $offre->getIdOffre()]),
                'editUrl' => $this->generateUrl('admin_offre_delete', ['idOffre' => $offre->getIdOffre()])
            ];
        }

        return new JsonResponse($response);
    }
}
