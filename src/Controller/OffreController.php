<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\User;
use App\Entity\Avis;
use App\Form\OffreType;
use App\Form\AvisType;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/offre')]
class OffreController extends AbstractController
{
    #[Route('/search', name: 'offre_search')]
    public function search(EntityManagerInterface $entityManager, Request $request, OffreRepository $offreRepository): Response
    {
        $query = $request->query->get('q');
        $offres = $offreRepository->findByDestination($query);
        $avis = $entityManager
            ->getRepository(Avis::class)
            ->findAll();

        return $this->render('offre/search.html.twig', [
            'offres' => $offres,
            'avis' => $avis,
            'query' => $query,
        ]);
    }

    #[Route('/', name: 'app_offre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request, OffreRepository $offreRepository): Response
    {
        $offres = $entityManager
            ->getRepository(Offre::class)
            ->findAll();
        $avis = $entityManager
            ->getRepository(Avis::class)
            ->findAll();
        $query = $request->query->get('q');
        $offres = $offreRepository->findByDestination($query);

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
            'avis' => $avis,
            'query' => $query,
        ]);
    }

    #[Route('/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre = new Offre();
       
       // $user = $entityManager->find(User::class, 1);
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('offre')['imageVehicule'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            // dd($offre);
            $offre->setImageVehicule($filename);
            //$offre->setIdUser($user);
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{idOffre}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 
        $username=$user->getUsername();
        $avis = $entityManager
            ->getRepository(Avis::class)
            ->findAll();

        foreach ($avis as $key => $avi) {
            if ($avi->getIdOffre() != $offre) {
                unset($avis[$key]);
            }
        }

        $averageRate = array_reduce($avis, function($carry, $item) {
            return $carry + $item->getRate();
        });
        
        $averageRate /= count($avis);
        $averageRate = number_format($averageRate, 0);
        $averageRate = intval($averageRate);

        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
            'avis' => $avis,
            'avgRate' => $averageRate,
            'username' => $username,
        ]);
    }

    #[Route('/{idOffre}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        //$user = $entityManager->find(User::class, 1);
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('offre')['imageVehicule'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            // dd($offre);
            $offre->setImageVehicule($filename);
           // $offre->setIdUser($user);
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{idOffre}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getIdOffre(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idOffre}/avis/add', name: 'app_offre_avis', methods: ['GET', 'POST'])]
    
    public function addAvis(Request $request, Offre $offre, EntityManagerInterface $entityManager)
{
   // $user = $entityManager->find(User::class, 1);
   $user=$this->getUser();  
    $avis = new Avis();
    $avis->setIdOffre($offre);
    $avis->setIdUser($user);
    $form = $this->createForm(AvisType::class, $avis);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($avis);
        $entityManager->flush();
        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('offre/avis.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
