<?php

namespace MDN\AdminBundle\Controller;

use MDN\AdminBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

 
/**
 *
 */
class LoginController extends Controller
{

    /**
     *
     * @return array
     * @Template("MDNAdminBundle:Login:login.html.twig")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        // I should move it to a form service
        $builder = $this->container->get('form.factory')
                ->createNamedBuilder(null, 'form', array(
                    '_target_path' => $this->generateUrl('mdn_admin_index_index'),
                ), array());

        $form = $builder->add('_target_path', 'hidden')
                ->add('_username', 'email')
                ->add('_password', 'password')
                ->getForm();
        
        return array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error,
            'form' => $form->createView(),
        );
    }

    /**
     *
     */
    public function loginCheckAction()
    {
        
    }

    /**
     *
     */
    public function logoutAction()
    {
        
    }

}
