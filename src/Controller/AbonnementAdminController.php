<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Offre2;
use App\Form\AbonnementType;
use App\Repository\AbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/admin/abonnement')]
class AbonnementAdminController extends AbstractController
{
    private $abonnementRepository;

    public function __construct(AbonnementRepository $abonnementRepository)
    {
        $this->abonnementRepository = $abonnementRepository;
    }
    
    #[Route('/sortByAscDate', name: 'sort_by_asc_date')]
    public function sortAscDate(AbonnementRepository $abonnementRepository, Request $request)
    {
        $abonnements = $abonnementRepository->sortByAscDate();
    
        return $this->render("abonnementAdmin/index.html.twig",[
            'abonnements' => $abonnements,
        ]);
    }
    
    #[Route('/sortByDescDate', name: 'sort_by_desc_date')]
    public function sortDescDate(AbonnementRepository $abonnementRepository, Request $request)
    {
        $abonnements = $abonnementRepository->sortByDescDate();
    
        return $this->render("abonnementAdmin/index.html.twig",[
            'abonnements' => $abonnements,
        ]);
    }

    #[Route('/', name: 'admin_abonnement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $abonnements = $entityManager
            ->getRepository(Abonnement::class)
            ->findAll();

        return $this->render('abonnementAdmin/index.html.twig', [
            'abonnements' => $abonnements,
        ]);
    }

    #[Route('/{id}', name: 'admin_abonnement_show', methods: ['GET'])]
    public function show(Abonnement $abonnement): Response
    {
        return $this->render('abonnementAdmin/show.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }
    
    #[Route ('/printabonnement/{id}', name: 'print_abonnement')]
    public function exportAbonnementPDF($id, AbonnementRepository $repo)
    {
        // On définit les options du PDF
        $pdfOptions = new Options();
        // Police par défaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf();
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
        $abonnement = $repo->find($id);
        // dd($abonnements);

        // On génère le html
        $html = $this->renderView(
            'abonnementAdmin/print.html.twig',
            [
                'abonnement' => $abonnement
            ]
        );

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère un nom de fichier
        $fichier = 'abonnement'. $abonnement->getIdentifiant() . date('c') .'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }

    #[Route ('/printallabonnements/{id}', name: 'print_abonnements')]
    public function exportAllAbonnementsPDF(AbonnementRepository $repo)
    {
        // On définit les options du PDF
        $pdfOptions = new Options();
        // Police par défaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
        $abonnements = $repo->findAll();
        // dd($abonnements);

        // On génère le html
        $html = $this->renderView(
            'abonnementAdmin/printall.html.twig',
            [
                'abonnements' => $abonnements
            ]
        );

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère un nom de fichier
        $fichier = 'abonnements'. date('c') .'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }

    public function searchAbonnement(Request $request)
    {
        $searchTerm = $request->query->get('searchTerm');

        if ($searchTerm === '') {
            $abonnements = $this->abonnementRepository->findAll();
        } else {
            $abonnements = $this->abonnementRepository->findByNom($searchTerm);
        }

        $response = [];
        foreach ($abonnements as $abonnement) {
            $response[] = [
                'nom' => $abonnement->getNom(),
                'prenom' => $abonnement->getPrenom(),
                'image' => $abonnement->getImage(),
                'email' => $abonnement->getEmail(),
                'identifiant' => $abonnement->getIdentifiant(),
                'cin' => $abonnement->getCin(),
                'type' => $abonnement->getType(),
                'dated' => $abonnement->getDated() ? $abonnement->getDated()->format('Y-m-d') : null,
                'datef' => $abonnement->getDatef() ? $abonnement->getDatef()->format('Y-m-d') : null,
                'prix' => $abonnement->getPrix(),
                'showUrl' => $this->generateUrl('admin_abonnement_show', ['id' => $abonnement->getId()]),
                'editUrl' => $this->generateUrl('print_abonnement', ['id' => $abonnement->getId()])
            ];
        }

        return new JsonResponse($response);
    }
}
