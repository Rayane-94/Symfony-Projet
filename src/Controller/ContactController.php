<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;



class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
    #[Route('/contact/add', name: 'contact')]
    public function add(ManagerRegistry $doctrine,Request $request)
    {
        $entityManager= $doctrine->getManager();

        $contact = new Contact();
        $contact->setCreatedAt(new \DateTimeImmutable());

        $formAnnonce = $this->createForm(FormulaireAnnonceType::class, $contact);

        $formAnnonce->handleRequest(($request));
        if ($formAnnonce->isSubmitted() && $formAnnonce->isValid()) {
           $entityManager->persist($contact);
           $entityManager->flush();

           $this->addFlash('success',"Le contact a bien été ajouter");
           return $this->redirectToRoute('app_home');
        }
        
        return $this->render('contact/form-add.html.twig',[
            'formAnnonce' => $formAnnonce->createView()]);

        //$entityManager->persist($annonce);
        //$entityManager->flush();

       // return new Response ("Bravo l'annonce a été add");
    }
    
}
    