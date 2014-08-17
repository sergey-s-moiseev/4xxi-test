<?php

namespace Sergey\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/friends")
 */
class FriendsController extends Controller
{
    /**
     * @Route("/facebook", name="facebook_list")
     * @Method("POST")
     */
    public function facebookListAction(Request $request)
    {
        $session = $this->get('session');
        if (is_null($session->get('fb_token')))
        {
            $session->getFlashBag()->add('error', 'You need to be authorized for access to friends');
            return $this->redirect($this->generateUrl('home'));
        }
        $facebook = new \Facebook([
            'appId' => $this->container->getParameter('facebook_id'),
            'secret' => $this->container->getParameter('facebook_secret')
        ]);
        $facebook->setAccessToken($session->get('fb_token'));
        // ToDo: for real application change to '/me/friends'
        $friends_list = $facebook->api('/me/taggable_friends');

        return new JsonResponse(['view' => $this->renderView(
            'SergeyTestBundle:Friends:friends_table.html.twig',
                [
                    'list' => $friends_list['data'],
                    'system' => false,
                    'allow_add' => false
                ]
        )]);
    }

    /**
     * @Route("/list", name="friends_list")
     * @Method("POST")
     */
    public function friendsListAction(Request $request)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $friends_list = $em ->getRepository("SergeyTestBundle:User")->findAll();

        return new JsonResponse(['view' => $this->renderView(
                'SergeyTestBundle:Friends:friends_table.html.twig',
                [
                    'list' => $friends_list,
                    'system' => true,
                    'allow_add' => true
                ]
            )]);
    }

    /**
     * @Route("/add", name="add_friend")
     * @Method("POST")
     */
    public function addFriendAction(Request $request)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /* @var $user \Sergey\TestBundle\Entity\User */
        $friend = $em ->getRepository("SergeyTestBundle:User")->findOneBy(['id' => $request->request->get('id')]);

        $user = $this->getUser();
        $user->addFriend($friend);

        $em->persist($user);
        $em->flush();

        return new JsonResponse($friend);
    }
}