<?php

namespace App\Controller;

use App\Entity\Offre2;
use App\Form\Offre2Type;
use App\Form\Offre2FilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/offre2')]
class Offre2AdminController extends AbstractController
{
    #[Route('/', name: 'offre2_indexx', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(Offre2FilterType::class);
        $form->handleRequest($request);

        $offre2Type = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $offre2Type = $form->getData()['offre2Type'];
        }

        $qb = $entityManager->createQueryBuilder();

        $qb->select('o')
           ->from(Offre2::class, 'o');

        if ($offre2Type !== null) {
            $qb->andWhere('o.type = :offre2Type')
               ->setParameter('offre2Type', $offre2Type);
        }

        $offre2s = $qb->getQuery()->getResult();

        $pagination = $paginator->paginate(
            $offre2s, // Requête contenant les données à paginer (ici nos offres)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        return $this->renderForm('offre2Admin/index.html.twig', [
            'offre2s' => $pagination,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'admin_offre2_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre2 = new Offre2();
        $form = $this->createForm(Offre2Type::class, $offre2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type = $offre2->getType();
            $entityManager->persist($offre2);
            $entityManager->flush();

            return $this->redirectToRoute('admin_offre2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre2Admin/new.html.twig', [
            'offre2' => $offre2,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_offre2_show', methods: ['GET'])]
    public function show(Offre2 $offre2): Response
    {
        return $this->render('offre2Admin/show.html.twig', [
            'offre2' => $offre2,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_offre2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre2 $offre2, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Offre2Type::class, $offre2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_offre2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre2Admin/edit.html.twig', [
            'offre2' => $offre2,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_offre2_delete', methods: ['POST'])]
    public function delete(Request $request, Offre2 $offre2, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre2->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre2);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_offre2_index', [], Response::HTTP_SEE_OTHER);
    }
}
