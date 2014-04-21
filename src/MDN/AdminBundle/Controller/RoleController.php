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

        $grid = $this->get('mdn_admin.grid.type.role')->build();

        $data = $this->setTemplateParams(array(
                    'title' => 'Role Edit',
                    'shortcuts' => array(
                        array('path' => 'mdn_admin_role_add', 'title' => 'Add New',),
                    ),
                ))
                ->renderTemplateParams();

        return $grid->getGridResponse($data);
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @Template("MDNAdminBundle:Role:edit.html.twig")
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm('role', null, array(
            'action' => $this->generateUrl('mdn_admin_role_add'),
                ));

        $form->handleRequest($request);

        if ($form->isValid()) {

            $roleEntity = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->merge($roleEntity);
            $em->flush();

            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The changes have been saved.');

            return $this->redirect($this->generateUrl('mdn_admin_role_index'));
        }

        return $this->setTemplateParams(array(
                            'title' => 'Role Add New',
                            'shortcuts' => array(
                                'path' => 'mdn_admin_role_index',
                                'title' => 'List',
                            ),
                        ))
                        ->renderTemplateParams(array(
                            'roleForm' => $form->createView(),
                        ));
    }
    

    /**
     * 
     * @param int $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @Template("MDNAdminBundle:Role:edit.html.twig")
     */
    public function editAction($id, Request $request)
    {

        $roleEntity = $this->getRepository('MDNAdminBundle:Role')->find($id);

        $form = $this->createForm('role', $roleEntity, array(
            'action' => $this->generateUrl('mdn_admin_role_edit', array(
                'id' => $id,
            )),
                ));

        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()
                    ->add('success', 'The changes have been saved.');

            return $this->redirect($this->generateUrl('mdn_admin_role_index'));
        }

        return $this->setTemplateParams(array(
                            'title' => 'Role Edit',
                            'shortcuts' => array(
                                array('path' => 'mdn_admin_role_index', 'title' => 'List',),
                                array('path' => 'mdn_admin_role_add', 'title' => 'Add New',),
                            ),
                        ))
                        ->renderTemplateParams(array(
                            'roleForm' => $form->createView(),
                        ));
    }
    
    /**
     * 
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $roleRepo = $em->getRepository('MDNAdminBundle:Role');
            $role = $roleRepo->find($id);

            if (!isset($role)) {
                throw new \Exception('Role not found.');
            }

            $role->setDeletedAt(new \Datetime());

            $this->get('session')->getFlashBag()
                    ->add('success', 'Record removed with success');
        } catch (\Exception $e) {

            $this->get('session')->getFlashBag()
                    ->add('error', $e->getMessage());
        }
        
        $em->flush();

        return $this->redirect($this->generateUrl('mdn_admin_role_index'));
    }
}
