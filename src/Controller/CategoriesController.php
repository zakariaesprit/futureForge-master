<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categories;
use App\Form\CategoriesType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\RecherchercategorieType;
use App\Repository\CategoriesRepository;


class CategoriesController extends AbstractController
{
    protected function json_response($data)
    {
        return new JsonResponse($data);
    }

    
     


    

    
     /**
     * @Route("/admin", name="display_admin")
     */
    public function indexAdmin(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Categories::class)->findAll();
        
        $sortOrder = $request->query->get('order', 'desc');
        return $this->render('Admin/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    /**
     * @Route("/Ajoutercategory", name="add_category")
     */
    public function addcategory(Request $request , EntityManagerInterface $entityManager): Response
    {
        $categories = new Categories();
        $form = $this->createForm(CategoriesType::class, $categories);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile= $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoFile->move(
                        $this->getParameter('Category_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $categories->setphoto($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();
            return $this->redirectToRoute('display_category');
            
        }
        return $this->render('category/Ajoutercategory.html.twig', ['c' => $form->createView()]);
    }
  /**
 * @Route("/afficher_categories", name="display_category")
 */
public function afficher_categories(): Response
{
    $em = $this->getDoctrine()->getManager();
    $categories = $em->getRepository(Categories::class)->findAll();
    
    return $this->render('category/Affichagecategory.html.twig', [
        'categories' => $categories,
    ]);
}


 /**
 * @Route("/modifcategory/{id}", name="modify_category")
 */
public function modifiercategory(Request $request, $id): Response
{
    $Categories = $this->getDoctrine()->getRepository(Categories::class)->find($id);
    $form = $this->createForm(CategoriesType::class, $Categories);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('display_category');
    }

    return $this->render('category/updatecategory.html.twig', [
        'form' => $form->createView(),
        'Categories' => $Categories
    ]);
}
     /**
     * @Route("/removecategory/{id}", name="supp_category")
     */
    public function supressioncategory(Categories $Categories): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($Categories);
        $em->flush();

        return $this->redirectToRoute('display_category');
    }
  
    }