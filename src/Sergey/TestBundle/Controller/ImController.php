<?php
namespace Sergey\TestBundle\Controller;

use Sergey\TestBundle\Entity\Message;
use Sergey\TestBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

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
            'facebook_id' => $this->container->getParameter('facebook_id')
        ];
    }

    /**
     * @Route("/messages", name="messages_ajax")
     * @Method("POST")
     */
    public function messagesAjaxAction(Request $request)
    {
        return new JsonResponse([
           [
               'user' => $this->getUser()->toArray(),
               'message' => "1232133"
           ]
        ]);
    }
}