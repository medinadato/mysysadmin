<?php

namespace MDN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity
 */
class Role
{
    /**
     * @var integer
     *
     * @ORM\Column(name="role_id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $roleId;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     * @Assert\Regex(
     *      pattern = "/[^a-z_\-0-9]/i",
     *      match = false,
     *      message = "Code must contain only letters, underscore or numbers"
     * )
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "5",
     *      max = "255",
     *      minMessage = "Your first name must be at least {{ limit }} characters length",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters length"
     * )
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "5",
     *      max = "255",
     *      minMessage = "Your first name must be at least {{ limit }} characters length",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters length"
     * )
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="role")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set roleId
     *
     * @param string $roleId
     * @return Role
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    
        return $this;
    }
    
    /**
     * Get roleId
     *
     * @return integer 
     */
    public function getRoleId()
    {
        return $this->roleId;
    }
    
    /**
     * Alias Get roleId
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->getRoleId();
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Role
     */
    public function setCode($code)
    {
        $this->code = strtoupper($code);
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }   

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Role
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    
        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
    
    /**
     * 
     * @return boolean
     */
    public function setEnabled($enabled)
    {
        $value = ($enabled === "Y") ? NULL : new \DateTime;
        
        $this->setDeletedAt($value);
    }
    
    /**
     * 
     * @return boolean
     */
    public function isEnabled()
    {
        return ($this->deletedAt === NULL) ? 'Y' : 'N';
    }
    
    /**
     * Add user
     *
     * @param \MDN\AdminBundle\Entity\User $user
     * @return Role
     */
    public function addUser(\MDN\AdminBundle\Entity\User $user)
    {
        $this->user[] = $user;
    
        return $this;
    }

    /**
     * Remove user
     *
     * @param \MDN\AdminBundle\Entity\User $user
     */
    public function removeUser(\MDN\AdminBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    
}
