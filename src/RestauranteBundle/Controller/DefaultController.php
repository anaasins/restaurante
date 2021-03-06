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

       return $this->redirectToRoute('listaTapas');
   }
        return $this->render('RestauranteBundle:Default:nuevaTapa.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Route("/listaTapas", name="listaTapas")
     */
    public function listaTapasAction()
    {
      //devolver la clase para interactuar con la BBDD
        $repository = $this->getDoctrine()->getRepository(Tapas::class);
      //sacar lo que queramos de la base de datos
        $tapas = $repository->findAll();
        return $this->render('RestauranteBundle:Default:listaTapas.html.twig', array('tapas'=>$tapas));
    }

    /**
     * @Route("/mostrarId/{id}", name="mostrarId")
     */
    public function mostrarIdAction($id)
    {
      //devolver la clase para interactuar con la BBDD
        $repository = $this->getDoctrine()->getRepository(Tapas::class);
      //sacar lo que queramos de la base de datos
        $tapas = $repository->findOneById($id);
        return $this->render('RestauranteBundle:Default:mostrarId.html.twig', array('tapas'=>$tapas));
    }

    /**
     * @Route("/eliminarTapa/{id}", name="eliminarTapa")
     */
    public function eliminarTapaAction($id)
    {
      $db=$this->getDoctrine()->getManager();
      $eliminar = $db ->getRepository(Tapas::class)->find($id);
      $db->remove($eliminar);
      $db->flush();
        return $this->redirectToRoute('listaTapas');
    }

    /**
     * @Route("/editarTapa/{id}", name="editarTapa")
     */
    public function editarTapaAction(Request $request, $id)
    {
      $tapa=$this->getDoctrine()->getRepository(Tapas::class)->find($id);

      $form=$this->createForm(TapasType::class, $tapa);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {

         //$cerveza = $form->getData();
         $em = $this->getDoctrine()->getManager();
         $em->persist($tapa);
         $em->flush();

         return $this->redirectToRoute('listaTapas');
       }

      return $this->render('RestauranteBundle:Default:editarTapa.html.twig', array('form'=>$form->createView()));
    }
}
