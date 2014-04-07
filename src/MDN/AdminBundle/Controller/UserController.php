<?php

namespace MDN\AdminBundle\Controller;

use MDN\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 */
class UserController extends Controller
{

    /**
     * 
     * @return array
     * @Template("MDNAdminBundle:User:index.html.twig")
     */
    public function indexAction()
    {
        // unsubscriber
        $userRepo = $this->getRepository('MDNAdminBundle:User');

        $users = $userRepo->findAll();
        
        return $this->setTemplateParams(array(
                            'title' => 'User Edit',
                            'shortcuts' => array(
                                array('path' => 'mdn_admin_user_add', 'title' => 'Add New',),
                            ),
                        ))
                        ->renderTemplateParams(array(
                            'users' => $users,
                        ));
    }

}
