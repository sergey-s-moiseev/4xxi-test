<?php
namespace Sergey\TestBundle\Controller;

use Sergey\TestBundle\Entity\Message;
use Sergey\TestBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Route("/im")
 */
class ImController extends Controller
{
    /**
     * @Route("/", name="instant_messenger")
     * @Template()
     */
    public function imAction(Request $request)
    {
        $session = $this->container->get('session');
        if (is_null($this->getUser())) {
            $session->getFlashBag()->add('error', 'You need to be authorized for access to chat');
            return $this->redirect($this->generateUrl('home'));
        }

        $form = $this->createForm(
            new MessageType(),
            new Message(),
            []
        );

        $form->handleRequest($request);

        return [
            'form' => $form->createView(),
        ];
    }
}