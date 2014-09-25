<?php

namespace MDN\AdminBundle\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class UserManager
{
    
    /**
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $encoderFactory;

    /**
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(Container $container)
    {
        $this->encoderFactory = $container->get('security.encoder_factory');
    }
   

    /**
     * 
     * @param UserInterface $user
     * @param string $plaintextPassword
     */
    public function setUserPassword(UserInterface $user, $plaintextPassword)
    {
        $hash = $this->encoderFactory->getEncoder($user)->encodePassword($plaintextPassword, $user->getSalt());
        $user->setPassword($hash);
    }

}