<?php

namespace MDN\AdminBundle\Controller;

use MDN\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        $grid = $this->get('mdn_admin.grid.type.user')->build();
        
        $data = $this->setTemplateParams(array(
                    'title' => 'User Edit',
                    'shortcuts' => array(
                        array('path' => 'mdn_admin_user_create', 'title' => 'Add New',),
                    ),
                ))
                ->renderTemplateParams();

        return $grid->getGridResponse($data);
    }

}
