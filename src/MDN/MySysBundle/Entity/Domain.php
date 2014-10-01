<?php

namespace MDN\MySysBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Domain
 *
 * @ORM\Table(name="domain", indexes={@ORM\Index(name="fk_domain_server1_idx", columns={"server_id"})})
 * @ORM\Entity
 */
class Domain
{
    /**
     * @var integer
     *
     * @ORM\Column(name="domain_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $domainId;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="root_path", type="string", length=255, nullable=true)
     */
    private $rootPath;

    /**
     * @var string
     *
     * @ORM\Column(name="host_conf_path", type="string", length=255, nullable=true)
     */
    private $hostConfPath;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \MDN\MySysBundle\Entity\Server
     *
     * @ORM\ManyToOne(targetEntity="MDN\MySysBundle\Entity\Server")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="server_id", referencedColumnName="server_id")
     * })
     */
    private $server;

    function setDomainId($domainId)
    {
        $this->domainId = $domainId;
        
        return $this;
    }

    function getDomainId()
    {
        return $this->domainId;
    }
    
    /**
     * Alias
     * @return int id
     */
    function getId()
    {
        return $this->getDomainId();
    }

    function setUrl($url)
    {
        $this->url = $url;
        
        return $this;
    }
    
    function getUrl()
    {
        return $this->url;
    }

    function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;
        
        return $this;
    }
    
    function getRootPath()
    {
        return $this->rootPath;
    }

    function setHostConfPath($hostConfPath)
    {
        $this->hostConfPath = $hostConfPath;
        
        return $this;
    }

    function getHostConfPath()
    {
        return $this->hostConfPath;
    }
    
    function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }

    function getCreatedAt()
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }

    function setServer(\MDN\MySysBundle\Entity\Server $server)
    {
        $this->server = $server;
        
        return $this;
    }

    function getServer()
    {
        return $this->server;
    }

}
