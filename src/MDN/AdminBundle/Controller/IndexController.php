<?php

namespace MDN\AdminBundle\Controller;

use MDN\AdminBundle\Controller;

/**
 * @author Renato Medina <medina@mdnsolutions.com>
 */
class IndexController extends Controller
{
    /**
     * 
     * @return array
     */
    public function indexAction()
    {
        return $this->render('MDNAdminBundle:Index:index.html.twig', array());
    }
}