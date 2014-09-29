<?php

namespace MDN\MySysBundle\Controller;

use MDN\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * 
 */
class ServerController extends Controller
{

    /**
     * 
     * @return array
     * @Template("MDNMySysBundle:Server:index.html.twig")
     */
    public function indexAction()
    {

        $grid = $this->get('mdn_my_sys.grid.type.server')->build();

        $this->setTemplateParams(array(
            'template_title' => 'Server List',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_my_sys_server_create',
                    'title' => 'Add New',
                ),
            ),
        ));

        return $grid->getGridResponse($this->getTemplateParams());
    }

}
