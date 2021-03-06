<?php

namespace ClientesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ClientesBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use ClientesBundle\Form\UserType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexClientes")
     */
    public function indexAction()
    {
        return $this->render('ClientesBundle:Default:index.html.twig');
    }

    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('indexClientes');
        }

        return $this->render(
            'ClientesBundle:Default:register.html.twig',
            array('form' => $form->createView())
        );
    }
}
