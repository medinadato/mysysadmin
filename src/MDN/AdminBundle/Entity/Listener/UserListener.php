<?php

namespace MDN\AdminBundle\Entity\Listener;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Event\LifecycleEventArgs;
use MDN\AdminBundle\Entity\User;

class UserListener
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container $container
     */
    private $container;
    
//    /**
//     * 
//     * @param \Symfony\Component\DependencyInjection\Container $container
//     */
//    public function __construct(Container $container) {
//        $this->container = $container;
//        die('a');
//    }
    
    /**
     * 
     * @param \MDN\AdminBundle\Entity\User $user
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $event
     */
    public function prePersist(User $user, LifecycleEventArgs $event)
    {
        
//        $entityManager = $event->getEntityManager();
//        
//        $user->setPassword('88');
//        
//        $entityManager->merge($user);
//        $entityManager->flush();
    }
}