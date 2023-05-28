<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Offre2;
use App\Entity\User;
use App\Form\AbonnementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/abonnement')]
class AbonnementController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/', name: 'app_abonnement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->find(User::class, 1);//$user=$this->getUser();
        $abonnements = $entityManager
            ->getRepository(Abonnement::class)
            ->findAll();

        foreach ($abonnements as $key => $abonnement) {
            if ($abonnement->getUser() != $user) {
                unset($abonnements[$key]);
            }
        }

        return $this->render('abonnement/index.html.twig', [
            'abonnements' => $abonnements,
        ]);
    }

    #[Route('/new/{id}', name: 'app_abonnement_new', methods: ['GET', 'POST'])]
    public function new(Offre2 $offre2, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user=$this->getUser();
        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('abonnement')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $abonnement->setImage($filename);
            $abonnement->setType($offre2->getType());
            $abonnement->setPrix(120 - (120 * $offre2->getReduction() / 100));
            $abonnement->setIdOffre($offre2);
            $abonnement->setDated($offre2->getDated());
            $abonnement->setDatef($offre2->getDatef());
            $abonnement->setUser($user);
            // dd($abonnement);
            $entityManager->persist($abonnement);
            $entityManager->flush();
            
            // Create a new Email object and set its properties
            $email = (new Email())
            ->from('ghada.bensaidmeddeb@esprit.tn')
            ->to($abonnement->getEmail())
            ->subject('Abonnement confirmé')
            ->html(
                $this->renderView(
                    'emails/abonnementconfirmed.html.twig',
                    ['abonnement' => $abonnement]
                )
            );
            // ->text("Salut" . $abonnement->getNom() . " " . $abonnement->getPrenom() . ". Votre abonnement pour l'offre " . $abonnement->getIdOffre()->getNom() . ' a été confirmé avec succès. Merci pour votre confiance');

            $this->mailer->send($email);

            return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
            'offre2' => $offre2
        ]);
    }

    #[Route('/{id}', name: 'app_abonnement_show', methods: ['GET'])]
    public function show(Abonnement $abonnement): Response
    {
        return $this->render('abonnement/show.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_abonnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Abonnement $abonnement, EntityManagerInterface $entityManager): Response
    {
        $offre2 = $abonnement->getIdOffre();
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('abonnement')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $abonnement->setImage($filename);
            // dd($abonnement);
            $entityManager->persist($abonnement);
            $entityManager->flush();

            return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_abonnement_delete', methods: ['POST'])]
    public function delete(Request $request, Abonnement $abonnement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($abonnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
    }
}