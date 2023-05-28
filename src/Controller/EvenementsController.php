<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Evenements;
use App\Form\EvenementsType;
use App\Entity\Categories;
use App\Repository\EvenementsRepository;
use App\Form\RechercherevenementType;



#[Route('/evenement')]
class EvenementsController extends AbstractController
{
    #[Route('/', name: 'admin_offre2_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
    #[Route('/', name: 'display_admin')]
    public function indexAdmin(): Response
    {
        return $this->render('Admin/index.html.twig');
    
    }
    
#[Route('/Ajouterevent', name: 'add_event')]
public function addevent(Request $request): Response
{ 
    $Evenements = new Evenements();
    $form = $this->createForm(EvenementsType::class, $Evenements);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $categories = $form->get('Categories_id')->getData();
        $Evenements->setCategoriesId($categories);
        $em->persist($Evenements);
        $em->flush();

        return $this->redirectToRoute('display_event');
    }
    return $this->render('event/AjouterEvent.html.twig', [
        'e' => $form->createView(),
        'submit_button' => 'Ajouter',
    ]);
}
#[Route('/afficher-events', name: 'display_event')]
public function afficherEvents(Request $request): Response
{   $sort_by = $request->query->get('sort_by', 'date');
    $order = $request->query->get('order', 'asc');
    $Evenements = $this->getDoctrine()->getRepository(Evenements::class)
    ->findBy([], [$sort_by => $order]);


    // ... rest of the controller code

    return $this->render('event/Affichageevent.html.twig', [
       
        'sort_by' => $sort_by,
        'sort_order' => $order,
        'Evenements' => $Evenements,
    ]);
}

    
#[Route('/modifevent/{id}', name: 'modify_event')]
    public function modifierevent(Request $request, $id): Response
    {
        $Evenements = new Evenements();
$Categories_id = $Evenements->getCategoriesId(); // Accède à la propriété "categorie" via la méthode "getCategorie"
$Evenements->setCategoriesId($Categories_id); // Modifie la propriété "categorie" via la méthode "setCategorie"
        $Evenements = $this->getDoctrine()->getRepository(Evenements::class)->find($id);
$form = $this->createForm(EvenementsType::class, $Evenements);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('display_event');
        }
        return $this->render('event/updateevent.html.twig', ['form' => $form->createView(),'submit_button' => 'Modifier',]);
    }
    #[Route('/removeevent/{id}', name: 'supp_event')]
    public function supressionevent(Evenements $Evenements): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($Evenements);
        $em->flush();

        return $this->redirectToRoute('display_event');
    }
   
    #[Route('/search', name: 'event_search')]
        
            public function search(Request $request,EvenementsRepository $repo): Response
            {
                
                $form = $this->createForm(RechercherevenementType::class);
                        $form->handleRequest($request);
                    
                        if ($form->isSubmitted() && $form->isValid()) {
                            $data = $form->getData();
                            $criteria = [
                                'nom' => $data['nom'],
                                'Categories_id' => $data['Categories_id'],
                                'type' => $data['type']
                            ];
                    
                            $Evenements = $repo->findBySearchCriteria($criteria);
                            return $this->render('event/search.html.twig', [
                                'Evenements' => $Evenements,
                                'form' => $form->createView(),
                            ]);
                    
                            
                        }
                        
                return $this->render('event/search.html.twig', [
                    'Evenements' => [],
                    'form' => $form->createView(),
                ]);
            }
      #[Route('/stat', name: 'stat')]
      public function stat(EvenementsRepository $EvenementsRepository): Response
      {
          $EvenementsByMonth = $EvenementsRepository->countEvenementByMonth();
          $EvenementsByCategories_id = $EvenementsRepository->countEvenemenByCategories_id();
          
      
          return $this->render('event/statistique.html.twig', [
              'EvenementsByMonth' => $EvenementsByMonth,
              'EvenementsByCategories_id' =>$EvenementsByCategories_id,
              
              
          ]);
      }
      
       
            
             
}