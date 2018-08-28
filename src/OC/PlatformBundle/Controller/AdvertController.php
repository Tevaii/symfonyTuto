<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        // On récupère le service
        $antispam = $this->container->get('oc_platform.antispam');

        // Je pars du principe que $text contient le texte d'un message quelconque
        $text = '...';
        if ($antispam->isSpam($text)) {
            throw new \Exception('Votre message a été détecté comme spam !');
        }
    }

    public function viewAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if($advert === null){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // On liste les candidatures de cette annonce

        $listApplication = $em->getRepository('OCPlatformBundle:Application')->findBy(array('advert'=>$advert));

        return $this->render('OCPlatformBundle:Advert:view.html.twig',array('advert'=>$advert,'listApplication' => $listApplication));

    }

    public function addAction(Request $request)
    {
        // Création de l'entité Advert
        $image = new Image();
        $image->setAlt('Plage');
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');

        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor('Alexandre');
        $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");
        $advert->setImage($image);

        // Creation de la premiere candidature
        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");

        // Creation d'une deuxieme candidature
        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé");

        // On lie les candidatures à l'annonce
        $application2->setAdvert($advert);
        $application1->setAdvert($advert);

        // Recuperation de l'EntityManager
        $em = $this->getDoctrine()->getManager();
        $em->persist($application1);
        $em->persist($application2);
        $em->persist($advert);
        // On flush tout ce qui a été persisté avant
        $em->flush();

        // Reste de la méthode qu'on avait déjà écrit
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            // Puis on redirige vers la page de visualisation de cettte annonce
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert' => $advert,'id' => 1));

    }

    public function editAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        // Recuperation de l'annonce $id

        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if($advert === null){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas");
        }

        $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();


        foreach ($listCategories as $category){
            $advert->addCategory($category);
        }
        $em -> flush();
        
        return $this->render('OCPlatformBundle:Advert:edit.html.twig');

    }

    public function deleteAction($id)
    {
        // Ici, on récupérera l'annonce correspondant à $id

        // Ici, on gérera la suppression de l'annonce en question

        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}