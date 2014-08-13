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
     * @Route("/login", name="_login")
     */
    public function loginAction()
    {

        return [

        ];
    }

    /**
     * @Route("/login_check", name="_security_check")
     */
    public function securityCheckAction()
    {

    }

    /**
     * @Route("/logout", name="_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }


}
