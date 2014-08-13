<?php

namespace Sergey\TestBundle\Controller;

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookSession;
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
        return [
            'facebook_id' => $this->container->getParameter('facebook_id')
        ];
    }

    /**
     * @Route("/im", name="im")
     * @Template()
     */
    public function imAction()
    {
        $session = $this->container->get('session');
        if (is_null($this->getUser())) {
            $session->getFlashBag()->add('error', 'You need to be authorized for access to chat');
            return $this->redirect($this->generateUrl('home'));
        }
        return $this->render(
            'SergeyTestBundle:Default:im.html.twig',
            []
        );
    }

}
