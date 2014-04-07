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
        
        return $this->setTemplateParams(array(
                            'title' => 'Role Edit',
                            'shortcuts' => array(
                                array('path' => 'mdn_admin_user_add', 'title' => 'Add New',),
                            ),
                        ))
                        ->renderTemplateParams(array(
                            'roles' => $roles,
                        ));
    }
}
