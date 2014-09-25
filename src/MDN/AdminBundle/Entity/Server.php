<?php

namespace MDN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 *
 * @ORM\Table(name="server")
 * @ORM\Entity
 */
class Server
{
    /**
     * @var integer
     *
     * @ORM\Column(name="server_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $serverId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=false)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    function setServerId($serverId)
    {
        $this->serverId = $serverId;
        
        return $this;
    }

    function getServerId()
    {
        return $this->serverId;
    }    

    /**
     * 
     * @param string $name
     * @return \MDN\AdminBundle\Entity\Server
     */
    function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    function getName()
    {
        return $this->name;
    }
    
    /**
     * 
     * @param string $ip
     * @return \MDN\AdminBundle\Entity\Server
     */
    function setIp($ip)
    {
        $this->ip = $ip;
        
        return $this;
    }

    function getIp()
    {
        return $this->ip;
    }

    /**
     * 
     * @return string
     */
    function getCreatedAt()
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }

}
