<?php

namespace MDN\AdminBundle\Controller;

use MDN\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use MDN\AdminBundle\Entity\User as UserEntity;

/**
 * 
 */
class ServerController extends Controller
{

    /**
     * 
     * @return array
     * @Template("MDNAdminBundle:Server:index.html.twig")
     */
    public function indexAction()
    {

        $grid = $this->get('mdn_admin.grid.type.server')->build();

        $this->setTemplateParams(array(
            'template_title' => 'Server List',
//            'template_shortcuts' => array(
//                array(
//                    'path' => 'mdn_admin_server_create',
//                    'title' => 'Add New',
//                ),
//            ),
        ));

        return $grid->getGridResponse($this->getTemplateParams());
    }

}
