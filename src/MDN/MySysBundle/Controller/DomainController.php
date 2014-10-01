<?php

namespace MDN\MySysBundle\Controller;

use MDN\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MDN\MySysBundle\Entity\Domain as DomainEntity;

/**
 * @author Renato Medina <medina@mdnsolutions.com>
 */
class DomainController extends Controller
{

    /**
     * 
     * @return array
     * @Template("MDNMySysBundle:Domain:index.html.twig")
     */
    public function indexAction()
    {

        $grid = $this->get('mdn_my_sys.grid.type.domain')->build();

        $this->setTemplateParams(array(
            'template_title' => 'Domain List',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_my_sys_domain_create',
                    'title' => 'Add New',
                ),
            ),
        ));

        return $grid->getGridResponse($this->getTemplateParams());
    }

    /**
     * 
     * @param \MDN\MySysBundle\Entity\Domain $domain
     * @param array $params
     * @return boolean
     */
    private function processDomainForm(DomainEntity $domain, array $params = array())
    {
        /**
         * @var \Symfony\Component\Form\Form $form
         */
        $form = $this->createForm('domain', $domain, $params);

        // add form into the view
        $this->setTemplateParams(array('domainForm' => $form->createView(),));

        if ('POST' === $this->getRequest()->getMethod()) {

            $form->handleRequest($this->getRequest());

            if ($form->isValid()) {

                if ($domain->getId() === NULL) {
                    $this->getDoctrine()->getManager()->merge($domain);
                }

                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->add('success', 'The changes have been saved.');
            }
        }

        // update form into the view
        $this->setTemplateParams(array('domainForm' => $form->createView(),));

        return true;
    }

    /**
     * 
     * @return array
     * @Template("MDNMySysBundle:Domain:update.html.twig")
     */
    public function createAction()
    {
        // view template
        $this->setTemplateParams(array(
            'template_title' => 'Domain Add New',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_my_sys_domain_index',
                    'title' => 'List',
                ),
            ),
        ));

        $this->processDomainForm(new DomainEntity(), array(
            'action' => $this->generateUrl('mdn_my_sys_domain_create'),
        ));

        return $this->getTemplateParams();
    }

    /**
     * 
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     * @Template("MDNMySysBundle:Domain:update.html.twig")
     */
    public function updateAction($id)
    {
        // view template
        $this->setTemplateParams(array(
            'template_title' => 'Domain Edit',
            'template_shortcuts' => array(
                array(
                    'path' => 'mdn_my_sys_domain_index',
                    'title' => 'List',
                ),
                array(
                    'path' => 'mdn_my_sys_domain_create',
                    'title' => 'Add New',
                ),
            ),
        ));

        $domain = $this->getRepository('MDNMySysBundle:Domain')->find($id);

        if ($domain === NULL) {
            throw new \RuntimeException('Domain not found.');
            // return new Response('Domain not found.', 500);
        }

        $this->processDomainForm($domain, array(
            'action' => $this->generateUrl('mdn_my_sys_domain_update', array('id' => $id,)),
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

//            $em->getRepository('MDNMySysBundle:Domain')
//                    ->remove($id);

            $this->get('session')->getFlashBag()->add('success', 'Record removed with success');
        } catch (\RuntimeException $e) {

            $this->get('session')->getFlashBag()->add('error', $e->getMessage());
        }

        return $this->redirect($this->generateUrl('mdn_my_sys_domain_index'));
    }
}
