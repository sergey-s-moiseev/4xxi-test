<?php

namespace Sergey\TestBundle\Controller;

use Sergey\TestBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        if (!is_null($this->getUser())) {
            return $this->redirect($this->generateUrl('instant_messenger'));
        }
        return [
            'facebook_id' => $this->container->getParameter('facebook_id')
        ];
    }

    /**
     * @Route("/profile", name="profile")
     * @Template()
     */
    public function profileAction(Request $request)
    {
        $session = $this->container->get('session');
        if (is_null($user = $this->getUser())) {
            $session->getFlashBag()->add('error', 'You need to be authorized for access to edit profile');
            return $this->redirect($this->generateUrl('home'));
        }

        $form = $this->createForm(
            new UserType(),
            $user,
            []
        );

        $form->handleRequest($request);

        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Profile updated');

            return $this->redirect($this->generateUrl('profile'));
        }
        elseif($form->isSubmitted())
        {
            $this->get('session')->getFlashBag()->add('error', 'Error while submitting form');
        }

        return [
            'form' => $form->createView(),
        ];
    }

}
