<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdvertController extends Controller
{


    public function viewAction($id,Request $request)
    {
        //Récupération de la session
        $session = $request->getSession();

        //recuperation de l'id du user
        $userID = $session->get('user_id');

        //modification de l'id du user
        $session->set('user_id',$id);

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'id' => $id
        ));

    }

    public function addAction(Request $request)
    {
        //recupération de la session
        $session = $request->getSession();
        //ajout d'un message flash à la session
        $session->getFlashBag()->add('info','Annonce bien enregistrée');

        $session->getFlashBag()->add('info','Oui oui, elle est bien enregistrée !');

        return $this->redirectToRoute('oc_platform_view',array('id'=>5));
    }

    public function returnJsonAction($id)
    {
        //création d'une reponse en json composé d'un id
        $response = new Response(json_encode(array('id'=>$id)));

        //Modification de l'entete de la reponse pour signaler au navigateur que l'on renvoi du JSON
        $response->headers->set('content-type','application/json');

        //Alternativ return new JsonResponse(array('id'=>$id))
        return $response;
    }
    //Décomposition de la composition d'un objet response
    public function errorAction($id)
    {
        //Creation de la réponse
        $response = new Response();

        //Definition du contenu
        $response->setContent("Ceci est une page d'erreur 404");

        //Definition du code HTTP à "Not Found"

        $response->setStatusCode(Response::HTTP_NOT_FOUND);

        //On retourne la réponse
        return $response;
    }

    //Creation d'une vue plus complexe avec un slug

    public function viewSlugAction($slug,$year,$format)
    {
        return new Response(
            "Affichage de l'année '".$year."
            ' du format '".$format."' et du slug '".$slug."'.
            "
        );
    }


    //fonction de génération d'une url
    public function indexAction()
    {
        //génération d'une url pour l'annonce 5
        //1er argument : nom de la route
        //2eme argument valeurs des paramètres
        //$url = $this->get('router')->generate('oc_platform_view',array('id'=>5));

        //url vaut : "/platform/advert/5"
        return new Response("Ceci est la Home page!!");
    }
}
?>