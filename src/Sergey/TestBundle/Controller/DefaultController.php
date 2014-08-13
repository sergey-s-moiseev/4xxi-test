<?php

namespace Sergey\TestBundle\Controller;

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


}
