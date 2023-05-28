<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Offre;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

#[Route('/admin/avis')]
class AvisAdminController extends AbstractController
{
    #[Route('/sortByAscRating', name: 'sort_by_asc_rating')]
    public function sortAscRating(EntityManagerInterface $entityManager, AvisRepository $avisRepository, Request $request)
    {
        $avis = $entityManager
            ->getRepository(Avis::class)
            ->findAll();
        $offres = $entityManager
            ->getRepository(Offre::class)
            ->findAll();
            
        $pieChart = new PieChart();

        $charts = [['Avis', 'Number per Offre']];

        foreach ($offres as $o) {
            $offreN = 0;
            foreach ($avis as $a) {
                if ($o == $a->getIdOffre()) {
                    $offreN++;
                }
            }

            array_push($charts, [$o->getDestination(), $offreN]);
        }
        
        $pieChart->getData()->setArrayToDataTable($charts);

        // dd($pieChart);

        $pieChart->getOptions()->setTitle('Offre reach by Avis');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setColor('#07600');
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setFontSize(25);
        $avis = $avisRepository->sortByAscRating();
    
        return $this->render("avisAdmin/index.html.twig",[
            'avis' => $avis,
            'piechart' => $pieChart,
        ]);
    }
    
    #[Route('/sortByDescRating', name: 'sort_by_desc_rating')]
    public function sortDescRating(EntityManagerInterface $entityManager, AvisRepository $avisRepository, Request $request)
    {
        $avis = $entityManager
            ->getRepository(Avis::class)
            ->findAll();
        $offres = $entityManager
            ->getRepository(Offre::class)
            ->findAll();
            
        $pieChart = new PieChart();

        $charts = [['Avis', 'Number per Offre']];

        foreach ($offres as $o) {
            $offreN = 0;
            foreach ($avis as $a) {
                if ($o == $a->getIdOffre()) {
                    $offreN++;
                }
            }

            array_push($charts, [$o->getDestination(), $offreN]);
        }
        
        $pieChart->getData()->setArrayToDataTable($charts);

        // dd($pieChart);

        $pieChart->getOptions()->setTitle('Offre reach by Avis');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setColor('#07600');
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setFontSize(25);
        $avis = $avisRepository->sortByDescRating();
    
        return $this->render("avisAdmin/index.html.twig",[
            'avis' => $avis,
            'piechart' => $pieChart,
        ]);
    }

    #[Route('/', name: 'admin_avis_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $avis = $entityManager
            ->getRepository(Avis::class)
            ->findAll();
        $offres = $entityManager
            ->getRepository(Offre::class)
            ->findAll();
            
        $pieChart = new PieChart();

        $charts = [['Avis', 'Number per Offre']];

        foreach ($offres as $o) {
            $offreN = 0;
            foreach ($avis as $a) {
                if ($o == $a->getIdOffre()) {
                    $offreN++;
                }
            }

            array_push($charts, [$o->getDestination(), $offreN]);
        }
        
        $pieChart->getData()->setArrayToDataTable($charts);

        // dd($pieChart);

        $pieChart->getOptions()->setTitle('Offre reach by Avis');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setColor('#07600');
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setFontSize(25);

        return $this->render('avisAdmin/index.html.twig', [
            'avis' => $avis,
            'piechart' => $pieChart,
        ]);
    }

    #[Route('/new', name: 'admin_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avi = new Avis();
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avi);
            $entityManager->flush();

            return $this->redirectToRoute('admin_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avisAdmin/new.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{idAvis}', name: 'admin_avis_show', methods: ['GET'])]
    public function show(Avis $avi): Response
    {
        return $this->render('avisAdmin/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    #[Route('/{idAvis}/edit', name: 'admin_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avisAdmin/edit.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{idAvis}', name: 'admin_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getIdAvis(), $request->request->get('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_avis_index', [], Response::HTTP_SEE_OTHER);
    }
}
