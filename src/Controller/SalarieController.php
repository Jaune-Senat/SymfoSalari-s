<?php

namespace App\Controller;

use App\Entity\Salarie;
use App\Form\SalarieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/salaries")
 */
class SalarieController extends AbstractController
{
    /**
     * @Route("/", name="salaries_index")
     */
    public function index()
    {
        $salaries = $this->getDoctrine()
            ->getRepository(Salarie::class)
            ->findBy([], ["dateEmbauche" => "DESC"]);
    
        return $this->render('salarie/index.html.twig', [
            'salaries' => $salaries,
        ]);
    }

    /**
     * @Route("/add", name="salarie_add")
     * @Route("/{id}/edit", name="salarie_edit")
     */
    public function add_edit(Salarie $salarie = null, Request $request){
        // si le salarie n'existe pas, on instancie un nouveau Salarie (on est dans le cas d'un ajout )
        if(!$salarie){
            $salarie = new Salarie();
        }

        // il faut créer un SalarieType au préalable (php bin/console make:form)
        $form = $this->createForm(SalarieType::class, $salarie);

        $form->handleRequest($request);
        //si on soumet le formulaire et que le form est valide
        if($form->isSubmitted() && $form->isValid()){
            // on récupère les données du formulaire
            $salarie = $form->getData();
            // on ajoute le nouveau salarié
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($salarie);
            $entityManager->flush();
            // on redirige vers la liste des salariés (salarie_list étant le nom de la route qui liste tous les salariés dans SalarieController)
            return $this->redirectToRoute('salaries_index');
        }

        /* on renvoie à la vue correspondante :
            - le formulaire
            - le booléen editMode (si vrai, on est dans le cas d'une édition sinon on est dans le cas d'un ajout)
         */
        return $this->render('salarie/add_edit.html.twig', [
            'formSalarie' => $form->createView(),
            'editMode' => $salarie->getId() !==null
        ]);
    }

    /**
     * @Route("/{id}/delete", name="salarie_delete") 
     */
    public function delete(Salarie $salarie)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($salarie);
        $entityManager->flush();
        
        return $this->redirectToRoute('salaries_index');

    }

    /**
     * @Route("/{id}", name="salarie_show", methods="GET")
     */
    public function show(Salarie $salarie): Response {
        return $this->render('salarie/show.html.twig',[
            'salarie' => $salarie
        ]);
    }
}
