<?php
namespace Sergey\TestBundle\Controller;

use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Sergey\TestBundle\Entity\Message;
use Sergey\TestBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $session = $this->get('session');
        if (is_null($this->getUser())) {
            $session->getFlashBag()->add('error', 'You need to be authorized for access to chat');
            return $this->redirect($this->generateUrl('home'));
        }
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $messages = $em ->getRepository("SergeyTestBundle:Message")
                        ->createQueryBuilder("m")
                        ->setMaxResults(10)
                        ->orderBy('m.created', 'DESC')
                        ->getQuery()
                    ->getResult();

        return [
            'form' => $this->generateMessageForm(new Message())->createView(),
            'facebook_id' => $this->container->getParameter('facebook_id'),
            'messages' => $messages
        ];
    }

    /**
     * @Route("/messages_list", name="messages_list_ajax")
     * @Method("POST")
     */
    public function messagesListAjaxAction(Request $request)
    {
        $lastUpdate = new \DateTime($request->request->get('last_update'));
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $messages = $em ->getRepository("SergeyTestBundle:Message")
                        ->createQueryBuilder("m")
                        ->where("m.created > :last_ts")
                        ->setParameters(['last_ts' => $lastUpdate->format("Y-m-d H:i:s")])
                        ->orderBy('m.created', 'ASC')
                        ->getQuery()
                    ->getResult();
        $serializer = $this->container->get('jms_serializer');
        return new Response($serializer->serialize($messages, 'json'));
    }

    /**
     * @Route("/message", name="message_ajax")
     * @Method("POST")
     */
    public function messagesAjaxAction(Request $request)
    {
        $_request = $request->request->get('sergey_testbundle_message');
        if (isset($_request['id']))
        {
            $em = $this->getDoctrine()->getManager();
            $message = $em->getRepository("SergeyTestBundle:Message")
                      ->findOneBy(['id' => $_request['id']]);
        }
        else
        {
            $message = new Message();
        }
        $form = $this->generateMessageForm($message);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $message->setUser($this->getUser());
            $em->persist($message);
            $em->flush();
        } else if ($form->isSubmitted()) {
           var_dump($form->getErrorsAsString());exit;
        }

        $serializer = $this->container->get('jms_serializer');
        return new Response($serializer->serialize($message, 'json'));
    }


    /**
     * @Route("/edit_form", name="edit_form_ajax")
     * @Method("POST")
     */
    public function editFormAjaxAction(Request $request)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $message = $em ->getRepository("SergeyTestBundle:Message")->findOneBy(['id' => $request->request->get('message_id')]);

        return new JsonResponse(['form' => $this->renderView("SergeyTestBundle:Form:edit_form.html.twig",
            [ 'form' => $this->generateMessageForm($message)->createView() ])
        ]);
    }

    private function generateMessageForm(Message $message)
    {
        $form = $this->createForm(
            new MessageType(),
            $message,
            [
                'ajax_action_url' => $this->generateUrl('message_ajax'),
                'edit_id' => $message->getId()
            ]
        );

        return $form;
    }
}