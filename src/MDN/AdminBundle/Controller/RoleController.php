<?php

namespace MDN\AdminBundle\Controller;

use MDN\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MDN\AdminBundle\Entity\Role as RoleEntity;

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

        $grid = $this->get('mdn_admin.grid.type.role')->build();

        $this->setTemplateParams(array(
            'template_title' => 'Role List',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_admin_role_create',
                    'title' => 'Add New',
                ),
            ),
        ));

        return $grid->getGridResponse($this->getTemplateParams());
    }

    /**
     * 
     * @param \MDN\AdminBundle\Entity\Role $role
     * @param array $params
     * @return boolean
     */
    private function processRoleForm(RoleEntity $role, array $params = array())
    {
        /**
         * @var \Symfony\Component\Form\Form $form
         */
        $form = $this->createForm('role', $role, $params);

        // add form into the view
        $this->setTemplateParams(array('roleForm' => $form->createView(),));

        if ('POST' === $this->getRequest()->getMethod()) {

            $form->handleRequest($this->getRequest());

            if ($form->isValid()) {

                if ($role->getId() === NULL) {
                    $this->getDoctrine()->getManager()->merge($role);
                }

                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->add('success', 'The changes have been saved.');
            }
        }

        // update form into the view
        $this->setTemplateParams(array('roleForm' => $form->createView(),));

        return true;
    }

    /**
     * 
     * @return array
     * @Template("MDNAdminBundle:Role:update.html.twig")
     */
    public function createAction()
    {
        // view template
        $this->setTemplateParams(array(
            'template_title' => 'Role Add New',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_admin_role_index',
                    'title' => 'List',
                ),
            ),
        ));

        $this->processRoleForm(new RoleEntity(), array(
            'action' => $this->generateUrl('mdn_admin_role_create'),
        ));

        return $this->getTemplateParams();
    }

    /**
     * 
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     * @Template("MDNAdminBundle:Role:update.html.twig")
     */
    public function updateAction($id)
    {
        // view template
        $this->setTemplateParams(array(
            'template_title' => 'Role Edit',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_admin_role_index',
                    'title' => 'List',
                ),
                array(
                    'path' => 'mdn_admin_role_create',
                    'title' => 'Add New',
                ),
            ),
        ));

        $role = $this->getRepository('MDNAdminBundle:Role')->find($id);

        if ($role === NULL) {
            throw new \RuntimeException('Role not found.');
            // return new Response('Role not found.', 500);
        }

        $this->processRoleForm($role, array(
            'action' => $this->generateUrl('mdn_admin_role_update', array('id' => $id,)),
        ));

        return $this->getTemplateParams();
    }

}
