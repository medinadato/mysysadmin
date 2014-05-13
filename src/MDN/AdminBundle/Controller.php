<?php

namespace MDN\AdminBundle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use MDN\AdminBundle\Controller\TokenAuthenticatedController;

/**
 * 
 */
class Controller extends SymfonyController implements TokenAuthenticatedController
{

    /**
     * 
     * @param string $namespace
     * @return object
     */
    protected function getRepository($namespace)
    {
        return $this->getDoctrine()
                        ->getManager()
                        ->getRepository($namespace);
    }
   
    /**
     *
     * @var array 
     */
    private $template_params = array();
    
    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return \MDN\AdminBundle\Controller
     */
    protected function setTemplateParam($key, $value) {
        $this->template_params[$key] = $value;
        
        return $this;
    }
    
    /**
     * Set parameters to be used by the default layout
     */
    protected function setTemplateParams($data = array())
    {
//        // data structure
//        $variables = array(
//            'title'     => null,
//            'shortcuts' => array(
//                array(
//                    'path'  => null,
//                    'title' => null,
//                )),
//        );
        
        foreach($data as $key => $value) {
            $this->setTemplateParam($key, $value);
        }
                
        return $this;
    }
    
    /**
     * 
     * @param array $data
     * @return array
     */
    protected function setTemplateShortcuts($data = array()) {
        
        // prepare values
        $values = isset($data['path']) ? array($data) : $data;
        
        foreach($values as $key => $value) {
            
            // checks path
            if(!isset($value['path']) || empty($value['path'])) {
                return false;
            }
            
            $router = $this->container->get('router');
            if(null !== $router->getRouteCollection()->get($value['path'])) {
                $values[$key]['path'] = $this->generateUrl($value['path']);
            }
            
            // checks title
            if(!isset($value['title']) || empty($value['title'])) {
                return false;
            }
        }
        
        return $values;
    }

    /**
     * 
     * @param array $extra_params Extra params to be send to the view
     * @return array Array of params 
     */
    protected function getTemplateParams($extra_params = array())
    {
        return array_merge($this->template_params, $extra_params);
    }

}
