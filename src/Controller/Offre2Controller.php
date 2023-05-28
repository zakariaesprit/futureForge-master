<?php

namespace App\Controller;

use App\Entity\Offre2;
use App\Form\Offre2Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use DateTime;

#[Route('/offre2')]
class Offre2Controller extends AbstractController
{
    #[Route('/', name: 'app_offre2_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('offre2/offre.html.twig', []);
    }

    #[Route('/mensuelle', name: 'app_offre2_men', methods: ['GET'])]
    public function men(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        // $offre2s = $entityManager
        //     ->getRepository(Offre2::class)
        //     ->findAll();
        
        $qb = $entityManager->createQueryBuilder();

        $qb->select('o')
        ->from(Offre2::class, 'o')
        ->where('o.dated > :today')
        ->setParameter('today', new \DateTime('today'));

        $upcomingOffre2s = $qb->getQuery()->getResult();

        foreach ($upcomingOffre2s as $key => $offre2) {
            if ($offre2->getType() != "MENSUELLE") {
                unset($upcomingOffre2s[$key]);
            }
        }

        $pagination = $paginator->paginate(
            $upcomingOffre2s, // Requête contenant les données à paginer (ici nos offres)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );

        return $this->render('offre2/index.html.twig', [
            'offre2s' => $pagination,
        ]);
    }

    #[Route('/semestrielle', name: 'app_offre2_sem', methods: ['GET'])]
    public function sem(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        // $offre2s = $entityManager
        //     ->getRepository(Offre2::class)
        //     ->findAll();
        
        $qb = $entityManager->createQueryBuilder();

        $qb->select('o')
        ->from(Offre2::class, 'o')
        ->where('o.dated > :today')
        ->setParameter('today', new \DateTime('today'));

        $upcomingOffre2s = $qb->getQuery()->getResult();

        foreach ($upcomingOffre2s as $key => $offre2) {
            if ($offre2->getType() != "SEMESTRIELLE") {
                unset($upcomingOffre2s[$key]);
            }
        }

        $pagination = $paginator->paginate(
            $upcomingOffre2s, // Requête contenant les données à paginer (ici nos offres)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );

        return $this->render('offre2/index.html.twig', [
            'offre2s' => $pagination,
        ]);
    }

    #[Route('/annuelle', name: 'app_offre2_ann', methods: ['GET'])]
    public function ann(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        // $offre2s = $entityManager
        //     ->getRepository(Offre2::class)
        //     ->findAll();
        
        $qb = $entityManager->createQueryBuilder();

        $qb->select('o')
        ->from(Offre2::class, 'o')
        ->where('o.dated > :today')
        ->setParameter('today', new \DateTime('today'));

        $upcomingOffre2s = $qb->getQuery()->getResult();

        foreach ($upcomingOffre2s as $key => $offre2) {
            $offreDate = $offre2->getDated()->format('d-m-Y');
            if ($offre2->getType() != "ANNUELLE") {
                unset($upcomingOffre2s[$key]);
            }
        }

        $pagination = $paginator->paginate(
            $upcomingOffre2s, // Requête contenant les données à paginer (ici nos offres)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );

        return $this->render('offre2/index.html.twig', [
            'offre2s' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_offre2_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre2 = new Offre2();
        $form = $this->createForm(Offre2Type::class, $offre2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type = $offre2->getType();
            if($type == "ANNUELLE") {
                $offre2->setReduction(30);
            } elseif ($type == "SEMESTRIELLE") {
                $offre2->setReduction(20);
            } else {
                $offre2->setReduction(10);
            }
            $entityManager->persist($offre2);
            $entityManager->flush();

            return $this->redirectToRoute('app_offre2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre2/new.html.twig', [
            'offre2' => $offre2,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offre2_show', methods: ['GET'])]
    public function show(Offre2 $offre2): Response
    {
        return $this->render('offre2/show.html.twig', [
            'offre2' => $offre2,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offre2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre2 $offre2, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Offre2Type::class, $offre2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offre2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre2/edit.html.twig', [
            'offre2' => $offre2,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offre2_delete', methods: ['POST'])]
    public function delete(Request $request, Offre2 $offre2, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre2->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre2);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offre2_index', [], Response::HTTP_SEE_OTHER);
    }
}
