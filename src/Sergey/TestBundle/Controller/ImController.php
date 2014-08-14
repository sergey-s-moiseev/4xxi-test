<?php
namespace Sergey\TestBundle\Controller;

use Sergey\TestBundle\Entity\Message;
use Sergey\TestBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
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


        return [
            'form' => $this->generateMessageForm(new Message())->createView(),
            'facebook_id' => $this->container->getParameter('facebook_id')
        ];
    }

    /**
     * @Route("/messages_list", name="messages_list_ajax")
     * @Method("POST")
     */
    public function messagesListAjaxAction(Request $request)
    {
        return new JsonResponse([
           [
               'user' => $this->getUser()->toArray(),
               'message' => "1232133"
           ]
        ]);
    }

    /**
     * @Route("/message/{id}", name="message_ajax", defaults={"id": null})
     * @Method("POST")
     */
    public function messagesAddEditAjaxAction(Request $request)
    {
        $message = new Message();
        $form = $this->generateMessageForm($message);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $message->setCreated(new \DateTime("now"));
            $message->setUser($this->getUser());

            $em->persist($message);
            $em->flush();
        }
        return new JsonResponse($request);
    }

    private function generateMessageForm(Message $message)
    {
        $form = $this->createForm(
            new MessageType(),
            $message,
            [
                'ajax_action_url' => $this->generateUrl('message_ajax')
            ]
        );
        return $form;
    }
}