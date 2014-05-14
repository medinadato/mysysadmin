<?php

namespace MDN\AdminBundle\EventListener;

use MDN\AdminBundle\Controller\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class TokenListener
{
    /**
     *
     * @var type 
     */
    private $tokens;

    /**
     * 
     * @param type $tokens
     */
    public function __construct($tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * 
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     * @return type
     * @throws AccessDeniedHttpException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure. This is not usual in Symfony2 but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }
        
//        if ($controller[0] instanceof TokenAuthenticatedController) {
//            $token = $event->getRequest()->query->get('token');
//            if (!in_array($token, $this->tokens)) {
//                throw new AccessDeniedHttpException('This action needs a valid token!');
//            }
//        }
    }
}