<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use  OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Form\AdvertEditType;

class AdvertController extends Controller
{
// Fonction page d'accueuil
  public function indexAction($page)
  {
    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
    if ($page < 1) {
      // s'il n'y a pas de page on affiche un message d'erreur
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }

	$repository = $this
  ->getDoctrine()
  ->getManager()
  ->getRepository('OCPlatformBundle:Advert')
;
		// on récupère les articles dans la bdd
    $listAdverts = $repository->findAll(array('published' => '1' ));
	foreach ($listAdverts as $advert) {

$advert->getContent();
}
	// Affichage de la page
    return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts
    ));
  }
// Fonction voir les articles
  public function viewAction($id)
  {
    // On récupère le repository
    $repository = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert')
    ;

    // On récupère l'entité correspondante à l'id $id
	
    $advert = $repository->find($id);

 
    // Cas si l'id n'existe pas
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // rendu de la page
    return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
      'advert' => $advert
    ));
  }
// Fonction ajouter article
  public function addAction(Request $request)
  {
  // Création d'un nouvel article
	$advert = new Advert();

	$form = $this->get('form.factory')->create(new AdvertType, $advert);

    $form->handleRequest($request);

    // On vérifie que les valeurs entrées sont correctes
    if ($form->isValid()) {
      // On enregistre notre objet $advert dans la base de données, par exemple
      $em = $this->getDoctrine()->getManager();
      $em->persist($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // On redirige vers le nouvel article crée
      return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
    }

    // À ce stade, le formulaire n'est pas valide
    return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }
// Fonction modification articles
  public function editAction($id, Request $request)
  {
       $em = $this->getDoctrine()->getManager();

    // On récupère l'article avec l'id $id
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

	$form = $this->get('form.factory')->create(new AdvertEditType, $advert);

    $form->handleRequest($request);

    // On vérifie que les valeurs entrées sont correctes

    if ($form->isValid()) {
      // On l'enregistre notre objet $advert dans la base de données, par exemple
      $em = $this->getDoctrine()->getManager();
      $em->persist($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // On redirige vers la nouvelle page
      return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
    }

    // À ce stade, le formulaire n'est pas valide car :

    return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function deleteAction($id)
  {
	// Fonction qui gère la suppression de l'article en question
    // Ici, on récupérera l'article correspondant à $id	
    $em = $this->getDoctrine()->getManager();

    // On récupère l'article $id
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

	// Si l'article n'existe pas
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

	$em->remove($advert);
	
	// commit
	$em->flush();


    return $this->render('OCPlatformBundle:Advert:delete.html.twig');
  }
  
  public function menuAction($limit)
  {
   // On recupere les info depuis la bdd
	$repository = $this
  ->getDoctrine()
  ->getManager()
  ->getRepository('OCPlatformBundle:Advert')
;
	
  $listAdverts = $repository->findBy(
  array('published' => '1' ), // Critere
  array('id' => 'desc'),        // Tri
  3,                              // Limite
  0                               // Offset
);

foreach ($listAdverts as $advert) {
$advert->getContent();
}

    return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
      'listAdverts' => $listAdverts
    ));
  }
}