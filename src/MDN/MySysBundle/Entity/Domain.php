<?php

namespace MDN\MySysBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Domain
 *
 * @ORM\Table(name="domain", indexes={@ORM\Index(name="fk_domain_server1_idx", columns={"server_id"})})
 * @ORM\Entity
 * 
 * @author Renato Medina <medina@mdnsolutions.com>
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

    /**
     * 
     * @param int $domainId
     * @return \MDN\MySysBundle\Entity\Domain
     */
    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;
        
        return $this;
    }
    
    /**
     * Get domainId
     *
     * @return integer 
     */
    public function getDomainId()
    {
        return $this->domainId;
    }

    /**
     * Alias
     * @return int
     */
    public function getId()
    {
        return $this->getDomainId();
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Domain
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set rootPath
     *
     * @param string $rootPath
     * @return Domain
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;

        return $this;
    }

    /**
     * Get rootPath
     *
     * @return string 
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * Set hostConfPath
     *
     * @param string $hostConfPath
     * @return Domain
     */
    public function setHostConfPath($hostConfPath)
    {
        $this->hostConfPath = $hostConfPath;

        return $this;
    }

    /**
     * Get hostConfPath
     *
     * @return string 
     */
    public function getHostConfPath()
    {
        return $this->hostConfPath;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Domain
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set server
     *
     * @param \MDN\MySysBundle\Entity\Server $server
     * @return Domain
     */
    public function setServer(\MDN\MySysBundle\Entity\Server $server = null)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return \MDN\MySysBundle\Entity\Server 
     */
    public function getServer()
    {
        return $this->server;
    }

}
