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
                ->getTemplateParams();

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

        $userEntity = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->merge($userEntity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'The changes have been saved.');

        return true;
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @Template("MDNAdminBundle:User:update.html.twig")
     */
    public function createAction(Request $request)
    {
        try {

            $form = $this->createForm('user', null, array(
                'action' => $this->generateUrl('mdn_admin_user_create'),
            ));

            if ($this->processForm($form)) {
                return $this->redirect($this->generateUrl('mdn_admin_user_index'));
            }
        } catch (\RuntimeException $e) {

            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        // template
        $this->setTemplateParams(array(
            'title' => 'User Add New',
            'shortcuts' => array(
                'path' => 'mdn_admin_user_index',
                'title' => 'List',
            ),
        ));

        // view return
        return $this->getTemplateParams(array(
                    'userForm' => $form->createView(),
        ));
    }

    /**
     * 
     * @param int $id
     * @return type
     * @throws NotFoundHttpException
     */
    public function deleteAction($id)
    {

        try {
            $em = $this->getDoctrine()->getManager();

            $roleRepo = $em->getRepository('MDNAdminBundle:User');
            $role = $roleRepo->find($id);

            if (!isset($role)) {
                throw new RuntimeException('User not found.');
            }

            $role->setDeletedAt(new \Datetime());
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Record removed with success');
        } catch (\RuntimeException $e) {

            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('mdn_admin_user_index'));
    }

}
