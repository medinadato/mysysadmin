<?php

namespace MDN\AdminBundle\Controller;

use MDN\AdminBundle\Controller;

/**
 * 
 */
class IndexController extends Controller
{
    /**
     * 
     * @return type
     */
    public function indexAction()
    {
        return $this->render('MDNAdminBundle:Index:index.html.twig', array());
    }
}
