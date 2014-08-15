<?php
namespace Sergey\TestBundle\Provider;

use DateTimeZone;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Sergey\TestBundle\Entity\User;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class Facebook implements OAuthAwareUserProviderInterface
{

    /**
     * @var mixed
     */
    protected $em;

    /**
     * @var mixed
     */
    protected $repository;

    /**
     * @var mixed
     */
    protected $session;

    public function __construct(ManagerRegistry $registry, Session $session) {
        $this->em = $registry->getManager();
        $this->session = $session;
        $this->repository = $this->em->getRepository("SergeyTestBundle:User");
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $full_name = $response->getRealName();
        $email = $response->getEmail();
        $avatar = isset($response->getResponse()['picture']) ?
            $response->getResponse()['picture']['data']['url'] : null;

        $this->session->set('fb_token', $response->getAccessToken());

        if (is_null($user = $this->loadUserByUsername($email))) {
            $name = explode(" ", $full_name);
            $user = new User();
            $user->setFacebookId($response->getResponse()['id']);

            $user->setFirstName($name[0]);
            $user->setLastName($name[1]);
            $user->setEmail($email);
            // Note: need to save avatar file to disk (use VichUploader), but it's takes some time
            $user->setPhotoFilename($avatar);
            $user->setUpdated(new \DateTime("now", new DateTimezone("europe/moscow")));
            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->repository->findOneBy(
            ['email' => $username]
        );
        return $user;
    }
}

