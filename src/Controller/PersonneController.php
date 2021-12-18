<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Personne;
use Symfony\Component\Serializer\SerializerInterface;

class PersonneController extends AbstractController
{
    /**
     * @Route("/personne", name="api")
     */
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/personne/ajouter", name="add_personne", methods={"POST"})
     * 
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     */
    public function AjouterPersonne(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        /* 
            Retourne toutes les informations de la personne ajoutée ou, en cas de problème sur l'âge, retourne un message d'erreur

            json structure : 
            {
                "prenom" : "younes",
                "nom" : "beriane",
                "date_naissance" : "24-03-1997"
            }
        */
        
        $data = $request->toArray();
        
        $personne = new Personne();

        $personne->setPrenom($data["prenom"]);
        $personne->setNom($data["nom"]);
        
        $personne->setDateNaissance(date('d-m-Y', strtotime($data["date_naissance"])));

        if($personne->getAge() >= 150){
            $message = "la personne que vous tentez d'ajouter à plus de 150 ans. Merci d'entrer une date de naissance valide.";
            return new Response($message);
        }

        $em->persist($personne);
        $em->flush();

        $json = $serializer->serialize($personne, 'json');

        return new Response($json);
    }

    /**
     * @Route("/personne/liste", name="liste_personnes", methods={"GET"})
     * 
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     */
    public function ListePersonnes(EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        /* 
           Retourne la liste de toutes les personnes de la base de données
        */
        $data = $this->getDoctrine()->getRepository('App:Personne')->findBy([], ['nom' => 'ASC']);

        $json = $serializer->serialize($data, 'json');

        return new Response($json);
    }
}
