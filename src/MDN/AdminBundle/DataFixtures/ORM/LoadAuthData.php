<?php

namespace MDN\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MDN\AdminBundle\Entity;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAuthData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    
    private $container;
    
    /**
     * The order in which fixtures will be loaded
     * 
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; 
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // add user
        $user = new Entity\User();
        $user->setUsername('admin@mdnsolutions.com');
        $user->setPassword($this->encodePassword($user, '123456'));
        
        // add roles
        $roles = array(
            array('ROLE_USER','User'),
            array('ROLE_ADMIN', 'Admin'),
        );
        
        foreach($roles as $role) {
            $entity = new Entity\Role();
            $entity->setCode($role[0]);
            $entity->setName($role[1]);
            $manager->persist($entity);
            
            $user->addRole($entity);
        }
        
        // link users to roles
        $manager->persist($user);
        
        # flush
        $manager->flush();
    }
    
    /**
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * 
     * @param type $entity
     * @param type $plainPassword
     * @return type
     */
    private function encodePassword($entity, $plainPassword)
    {
        $encoder = $this->container->get("security.encoder_factory")
                ->getEncoder($entity);
        
        return $encoder->encodePassword($plainPassword, $entity->getSalt());
    }
}