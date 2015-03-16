<?php

namespace MDN\MySysBundle\Entity;

//use MDN\MySysBundle\Entity\Server;
use Doctrine\ORM\EntityRepository;

/**
 * Description of ServerRepository
 *
 * @author Renato Medina <medina@mdnsolutions.com>
 */
class ServerRepository extends EntityRepository
{
    /**
     * 
     * @param string $id
     * @throws \RuntimeException
     */
    public function delete($id) {
        
        $em = $this->getEntityManager();

        $server = $this->find($id);

        if (!isset($server)) {
            throw new \RuntimeException('Server not found.');
        }
        
        $em->remove($server);

        $em->flush();
    }
}
