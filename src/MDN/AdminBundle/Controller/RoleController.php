<?php

namespace MDN\AdminBundle\Controller;

use MDN\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $data = $this->setTemplateParams(array(
                    'title' => 'Role Edit',
                    'shortcuts' => array(
                        array('path' => 'mdn_admin_role_create', 'title' => 'Add New',),
                    ),
                ))
                ->renderTemplateParams();

        return $grid->getGridResponse($data);
    }

    /**
     * 
     * @param type $form
     * @return boolean
     */
    private function processForm(\Symfony\Component\Form\Form $form)
    {
        $request = $this->getRequest();

        if ('POST' !== $request->getMethod()) {
            return false;
        }

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $roleEntity = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->merge($roleEntity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'The changes have been saved.');

        return true;
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @Template("MDNAdminBundle:Role:update.html.twig")
     */
    public function createAction(Request $request)
    {
        try {

            $form = $this->createForm('role', null, array(
                'action' => $this->generateUrl('mdn_admin_role_create'),
            ));

            if ($this->processForm($form)) {
                return $this->redirect($this->generateUrl('mdn_admin_role_index'));
            }
        } catch (\RuntimeException $e) {

            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        // template
        $this->setTemplateParams(array(
            'title' => 'Role Add New',
            'shortcuts' => array(
                'path' => 'mdn_admin_role_index',
                'title' => 'List',
            ),
        ));

        // view return
        return $this->renderTemplateParams(array(
                    'roleForm' => $form->createView(),
        ));
    }

    /**
     * 
     * @param int $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @throws NotFoundHttpException
     * @Template("MDNAdminBundle:Role:update.html.twig")
     */
    public function updateAction($id, Request $request)
    {
        
        try {

            $role = $this->getRepository('MDNAdminBundle:Role')->find($id);

            if ($role === NULL) {
                throw new \RuntimeException('Role not found.');
            }

            $form = $this->createForm('role', $role, array(
                'action' => $this->generateUrl('mdn_admin_role_update', array(
                    'id' => $id,
                )),
            ));

            if ($this->processForm($form)) {
                return $this->redirect($this->generateUrl('mdn_admin_role_index'));
            }

            $this->setTemplateParams(array(
                'title' => 'Role Edit',
                'shortcuts' => array(
                    array('path' => 'mdn_admin_role_index', 'title' => 'List',),
                    array('path' => 'mdn_admin_role_create', 'title' => 'Add New',),
                ),
            ));

            return $this->renderTemplateParams(array(
                        'roleForm' => $form->createView(),
            ));
            
        } catch (\RuntimeException $e) {

            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
            
            return $this->redirect($this->generateUrl('mdn_admin_role_index'));
        }
    }

}
