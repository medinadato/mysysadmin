<?php

namespace MDN\AdminBundle\Controller;

use MDN\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 */
class RoleController extends Controller
{
    /**
     * 
     * @return array
     * @Template("MDNAdminBundle:Role:index.html.twig")
     */
    public function indexAction()
    {
        // unsubscriber
        $roleRepo = $this->getRepository('MDNAdminBundle:Role');

        $roles = $roleRepo->findAll();

        return array(
            'content_title' => 'Role List',
            'roles' => $roles,
            );
    }
}
