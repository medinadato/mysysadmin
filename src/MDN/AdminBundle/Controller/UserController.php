<?php

namespace MDN\AdminBundle\Controller;

use MDN\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MDN\AdminBundle\Entity\User as UserEntity;

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

        $this->setTemplateParams(array(
            'template_title' => 'User List',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_admin_user_create',
                    'title' => 'Add New',
                ),
            ),
        ));

        return $grid->getGridResponse($this->getTemplateParams());
    }

    /**
     * 
     * @param \MDN\AdminBundle\Entity\User $user
     * @param array $params
     * @return boolean
     */
    private function processUserForm(UserEntity $user, array $params = array())
    {
        /**
         * @var \Symfony\Component\Form\Form $form
         */
        $form = $this->createForm('user', $user, $params);

        // add form into the view
        $this->setTemplateParams(array('userForm' => $form->createView(),));

        if ('POST' === $this->getRequest()->getMethod()) {

            $form->handleRequest($this->getRequest());

            if ($form->isValid()) {

                if ($user->getId() === NULL) {
                    $this->getDoctrine()->getManager()->merge($user);
                }
                
                // encrypt password
                $this->get('mdn_admin.user_manager')->setUserPassword($user, $form->get('password')->getData());

                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->add('success', 'The changes have been saved.');
            }
        }

        // update form into the view
        $this->setTemplateParams(array('userForm' => $form->createView(),));

        return true;
    }

    /**
     * 
     * @return array
     * @Template("MDNAdminBundle:User:update.html.twig")
     */
    public function createAction()
    {
        // view template
        $this->setTemplateParams(array(
            'template_title' => 'User Add New',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_admin_user_index',
                    'title' => 'List',
                ),
            ),
        ));

        $this->processUserForm(new UserEntity(), array(
            'action' => $this->generateUrl('mdn_admin_user_create'),
        ));

        return $this->getTemplateParams();
    }

    /**
     * 
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     * @Template("MDNAdminBundle:User:update.html.twig")
     */
    public function updateAction($id)
    {
        // view template
        $this->setTemplateParams(array(
            'template_title' => 'User Edit',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_admin_user_index',
                    'title' => 'List',
                ),
                array(
                    'path' => 'mdn_admin_user_create',
                    'title' => 'Add New',
                ),
            ),
        ));

        $user = $this->getRepository('MDNAdminBundle:User')->find($id);

        if ($user === NULL) {
            throw new \RuntimeException('User not found.');
            // return new Response('User not found.', 500);
        }

        $this->processUserForm($user, array(
            'action' => $this->generateUrl('mdn_admin_user_update', array('id' => $id,)),
        ));

        return $this->getTemplateParams();
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

            $userRepo = $em->getRepository('MDNAdminBundle:User');
            $user = $userRepo->find($id);

            if (!isset($user)) {
                throw new \RuntimeException('User not found.');
            }

            $user->setDeletedAt(new \Datetime());
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Record removed with success');
        } catch (\RuntimeException $e) {

            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('mdn_admin_user_index'));
    }

}
