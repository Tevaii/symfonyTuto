<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdvertController extends Controller
{

    //La route qui fait appel çà cette fonction est OCPlatorfmBundle:Adver:view
    //$id correspond à l'argument $id présent dans l'url (trucs/{id})
    //on injecte la requete dans les arguments de la fonction
    public function viewAction($id,Request $request)
    {
        $url = $this->get('router')->generate('oc_platform_home');

        return new RedirectResponse($url);

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