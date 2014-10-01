<?php

namespace MDN\MySysBundle\Controller;

use MDN\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MDN\MySysBundle\Entity\Server as ServerEntity;

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

    /**
     * 
     * @param \MDN\MySysBundle\Entity\Server $server
     * @param array $params
     * @return boolean
     */
    private function processServerForm(ServerEntity $server, array $params = array())
    {
        /**
         * @var \Symfony\Component\Form\Form $form
         */
        $form = $this->createForm('server', $server, $params);

        // add form into the view
        $this->setTemplateParams(array('serverForm' => $form->createView(),));

        if ('POST' === $this->getRequest()->getMethod()) {

            $form->handleRequest($this->getRequest());

            if ($form->isValid()) {

                if ($server->getId() === NULL) {
                    $this->getDoctrine()->getManager()->merge($server);
                }

                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->add('success', 'The changes have been saved.');
            }
        }

        // update form into the view
        $this->setTemplateParams(array('serverForm' => $form->createView(),));

        return true;
    }

    /**
     * 
     * @return array
     * @Template("MDNMySysBundle:Server:update.html.twig")
     */
    public function createAction()
    {
        // view template
        $this->setTemplateParams(array(
            'template_title' => 'Server Add New',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_my_sys_server_index',
                    'title' => 'List',
                ),
            ),
        ));

        $this->processServerForm(new ServerEntity(), array(
            'action' => $this->generateUrl('mdn_my_sys_server_create'),
        ));

        return $this->getTemplateParams();
    }

    /**
     * 
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     * @Template("MDNMySysBundle:Server:update.html.twig")
     */
    public function updateAction($id)
    {
        // view template
        $this->setTemplateParams(array(
            'template_title' => 'Server Edit',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_my_sys_server_index',
                    'title' => 'List',
                ),
                array(
                    'path' => 'mdn_my_sys_server_create',
                    'title' => 'Add New',
                ),
            ),
        ));

        $server = $this->getRepository('MDNMySysBundle:Server')->find($id);

        if ($server === NULL) {
            throw new \RuntimeException('Server not found.');
            // return new Response('Server not found.', 500);
        }

        $this->processServerForm($server, array(
            'action' => $this->generateUrl('mdn_my_sys_server_update', array('id' => $id,)),
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

            $serverRepo = $em->getRepository('MDNMySysBundle:Server');
            $server = $serverRepo->find($id);

            if (!isset($server)) {
                throw new \RuntimeException('Server not found.');
            }

            $server->setDeletedAt(new \Datetime());
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Record removed with success');
        } catch (\RuntimeException $e) {

            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('mdn_my_sys_server_index'));
    }
}
