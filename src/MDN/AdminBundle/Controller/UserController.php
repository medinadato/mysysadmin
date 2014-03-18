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

        return array(
            'content_title' => 'User List',
            'content_shortcuts' => array(
                array(
                    'path' => $this->generateUrl('mdn_admin_user_add'),
                    'title' => 'Add New',
                ),
            ),
            'users' => $users,
        );
    }

}
