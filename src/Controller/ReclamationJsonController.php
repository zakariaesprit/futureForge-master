<?php

namespace App\Controller;

use App\Entity\Reclamation;

use App\Repository\ReclamationRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Date;


/**
 * @Route ("/jsonReclamation")
 */
class ReclamationJsonController extends AbstractController
{
    ######################################afficher tous les Reclamations et les offres###########################
    /**
     * @Route("/Reclamation/liste")
     * @throws ExceptionInterface
     */
    public function listeReclamation(ReclamationRepository $ReclamationRepository, NormalizerInterface $normalizer)
    {
        $Reclamations = $ReclamationRepository->findAll();
        $jsonContent = $normalizer->normalize($Reclamations, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

###########################################################################################################
##############afficher par id ##########################################################################

    /**
     * @Route("/Reclamation/lire/{id}")
     */
    public function ReclamationId(Request $request, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $participation = $em->getRepository(Reclamation::class)->find($id);
        $jsonContent = $Normalizer->normalize($participation, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));

    }


###########################################################################################################
##############ajouter ##########################################################################


    /**
     * @Route("/Reclamation/ajout")
     */
    public function ajoutReclamation(Request $request, NormalizerInterface $normalizer, UserPasswordEncoderInterface $ReclamationPasswordEncoder)
    {
        // On vérifie si la requête est une requête Ajax

        $em = $this->getDoctrine()->getManager();
        $Reclamation = new Reclamation();
        $Reclamation->setTypeR($request->get('TypeR'));
        $Reclamation->setDescriptionR($request->get('DescriptionR'));
        $Reclamation->setObjet($request->get('Objet'));
        $datedebString = $request->get('DateR');
        $datedeb = \DateTime::createFromFormat('D M d H:i:s T Y', $datedebString);
        $Reclamation->setDateR($datedeb) ;
        $Reclamation->setEtat($request->get('etat'));

// On sauegarde en base
        $em->persist($Reclamation);
        $em->flush();
        $jsonContent = $normalizer->normalize($Reclamation, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));


    }




###########################################################################################################
##############supprimer ##########################################################################


    /**
     * @Route("/Reclamation/supprimer/{id}")
     */
    public function supprimReclamation(Request $request, SerializerInterface $serializer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $Reclamation = $em->getRepository(Reclamation::class)->find($id);
        $em->remove($Reclamation);
        $em->flush();
        $jsonContent = $serializer->serialize($Reclamation, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getIdUser();
            }
        ]);

        // On instancie la réponse
        $response = new Response($jsonContent);

        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        // On envoie la réponse
        return $response;


    }


###########################################################################################################
##############modifier ##########################################################################


    /**
     * @Route("/Reclamation/modif/{id}")
     */
    public function modifReclamation(Request $request, SerializerInterface $serializer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $Reclamation = $em->getRepository(Reclamation::class)->find($id);
        // On hydrate l'objet

        $Reclamation->setTypeR($request->get('TypeR'));
        $Reclamation->setDescriptionR($request->get('DescriptionR'));
        $Reclamation->setObjet($request->get('Objet'));
        $datedebString = $request->get('DateR');
        $datedeb = \DateTime::createFromFormat('D M d H:i:s T Y', $datedebString);
        $Reclamation->setDateR($datedeb) ;
        $Reclamation->setEtat($request->get('etat'));


        $em->flush();
        $jsonContent = $serializer->serialize($Reclamation, 'json', [
            'groups' => 'post:read',
            'circular_reference_handler' => function ($object) {
                return $object->getIdUser();
            }
        ]);


        // On instancie la réponse
        $response = new Response($jsonContent);

        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        // On envoie la réponse
        return $response;

    }


    /**
     * @Route("/Reclamation/liste/{id}")
     */
    public function listeReclamationparNom(ReclamationRepository $ReclamationRepository, NormalizerInterface $Normalizer, $id)
    {
        $jsonContent = array();
        $entityManager = $this->getDoctrine()->getManager();
        $Reclamation = $entityManager->getRepository(Reclamation::class)->findAll();
        $output = [];
        foreach ($Reclamation as $plc) {
            if ($plc->getTypeR() == $id) {

                $jsonContent1 = $Normalizer->normalize($plc);
                array_push($jsonContent, $jsonContent1);
            }
        }


        return new Response(json_encode($jsonContent));

    }


}

