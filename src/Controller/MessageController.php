<?php

namespace App\Controller;


use App\Entity\Message;
use App\Form\MessageEntityType;

use App\Repository\MessageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MessageController extends AbstractController
{
    /**
     * @Route("/Message", name="app_Message")
     */
    public function index(): Response
    {
        return $this->render('Messages/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }


    /**
     * @Route("/add_Message", name="add_Message")
     */

    public function Add(Request $request, ManagerRegistry $doctrine ) : Response {
        $Message =  new Message() ;
        $form =  $this->createForm(MessageEntityType::class,$Message) ;
        $form->add('Ajouter' , SubmitType::class) ;
        $form->handleRequest($request) ;

        if($form->isSubmitted()&& $form->isValid()){
            $Message = $form->getData();


            $em= $doctrine->getManager() ;
            $em->persist($Message);
            $em->flush();
            return $this ->redirectToRoute('add_Message') ;


        }

        return $this->render('messages/message.html.twig' , [
            'form' => $form->createView()
        ]) ;
    }

    /**
     * @Route("/afficher_msg", name="afficher_msg")
     */
    public function AfficheMessage (Request $request , MessageRepository $repo): Response
    {
        //$repo=$this ->getDoctrine()->getRepository(Message::class) ;
        $Message=$repo->findAll() ;

        return $this->render('messages/messageshow.html.twig' , [
            'message' => $Message ,
            'ajoutA' => $Message
        ]) ;
    }
    /**
     * @Route("/delete_msg/{id}", name="delete_msg")
     */
    public function Delete($id,MessageRepository $repository , ManagerRegistry $doctrine) : Response {
        $Message=$repository->find($id) ;
        $em=$doctrine->getManager() ;
        $em->remove($Message);
        $em->flush();
        return $this->redirectToRoute("afficher_msg") ;

    }
    /**
     * @Route("/update_msg/{id}", name="update_msg")
     */
    function update(MessageRepository $repo,$id,Request $request , ManagerRegistry $doctrine){
        $Message = $repo->find($id) ;
        $form=$this->createForm(MessageEntityType::class,$Message) ;
        $form->add('update' , SubmitType::class) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted()&& $form->isValid()){

            $Message = $form->getData();
            $em=$doctrine->getManager() ;
            $em->flush();
            return $this ->redirectToRoute('afficher_msg') ;
        }
        return $this->render('messages/updatemsg.html.twig' , [
            'form' => $form->createView()
        ]) ;

    }
}
