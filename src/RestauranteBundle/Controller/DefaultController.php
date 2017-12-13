<?php

namespace RestauranteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use RestauranteBundle\Entity\Tapas;
use RestauranteBundle\Form\TapasType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('RestauranteBundle:Default:index.html.twig');
    }

    /**
     * @Route("/nuevaTapa", name="nuevaTapa")
     */
    public function nuevaTapaAction(Request $request)
    {
      $tapas = new Tapas();
      $form = $this->createForm(TapasType::class, $tapas);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
       $tapa = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($tapa);
        $em->flush();

       return $this->redirectToRoute('index');
   }
        return $this->render('RestauranteBundle:Default:nuevaTapa.html.twig', array('form'=>$form->createView()));
    }
}
