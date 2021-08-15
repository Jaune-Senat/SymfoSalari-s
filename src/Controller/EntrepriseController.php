<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/entreprises")
 */
class EntrepriseController extends AbstractController
{
     /**
     * @Route("/", name="entreprise_index")
     */
    public function index()
    {
        $entreprises = $this->getDoctrine()
            ->getRepository(Entreprise::class)
            ->findBy([], ["raisonSociale" => "ASC"]);

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    /**
     * @Route("/add", name="entreprise_add")
     * @Route("/{id}/edit", name="entreprise_edit")
     */
    public function add_edit(Entreprise $entreprise = null, Request $request){
        // si l'entreprise n'existe pas, on instancie une nouvelle Entreprise (on est dans le cas d'un ajout )
        if(!$entreprise){
            $entreprise = new Entreprise();
        }

        // il faut créer un entrepriseType au préalable (php bin/console make:form)
        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);
        //si on soumet le formulaire et que le form est valide
        if($form->isSubmitted() && $form->isValid()){
            // on récupère les données du formulaire
            $entreprise = $form->getData();
            // on ajoute la nouvelle entreprise
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entreprise);
            $entityManager->flush();
            // on redirige vers la liste des entreprises (entreprise_list étant le nom de la route qui liste toutes les entreprises dans EntrepriseController)
            return $this->redirectToRoute('entreprise_index');
        }

        /* on renvoie à la vue correspondante :
            - le formulaire
            - le booléen editMode (si vrai, on est dans le cas d'une édition sinon on est dans le cas d'un ajout)
         */
        return $this->render('entreprise/add_edit.html.twig', [
            'formEntreprise' => $form->createView(),
            'editMode' => $entreprise->getId() !==null
        ]);
    }


    /**
     * @Route("/{id}/delete", name="entreprise_delete") 
     */
    public function delete(Entreprise $entreprise)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entreprise);
        $entityManager->flush();

        return $this->redirectToRoute('entreprise_index');

    }

    /**
     * @Route("/{id}", name="entreprise_show", methods="GET")
     */
    public function show(Entreprise $entreprise): Response {
        return $this->render('entreprise/show.html.twig',[
            'entreprise' => $entreprise
        ]);
    }
}
