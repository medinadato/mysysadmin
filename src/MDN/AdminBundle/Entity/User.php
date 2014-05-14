<?php

namespace MDN\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @Assert\GroupSequence({"User", "Strict"})
 * @ORM\EntityListeners({ "MDN\AdminBundle\Entity\Listener\UserListener" }) 
 */
class User implements AdvancedUserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(
     *      min = "1",
     *      max = "255"
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "6",
     *      max = "255"
     * )
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

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
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="user")
     * @ORM\JoinTable(name="user_role",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="role_id", referencedColumnName="role_id")
     *   }
     * )
     */
    private $role;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->role = new \Doctrine\Common\Collections\ArrayCollection();
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
 
    /*
     * Alias
     */
    public function getId()
    {
        return $this->userId;
    }
    
    /**
     * 
     * @param type $userId
     * @return \MDN\AdminBundle\Entity\User
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        
        return $this;
    }
        
    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        echo "porra";
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
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
     * @return User
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
     * Add role
     *
     * @param \MDN\AdminBundle\Entity\Role $role
     * @return User
     */
    public function addRole(\MDN\AdminBundle\Entity\Role $role)
    {
        $this->role[] = $role;
    
        return $this;
    }

    /**
     * Remove role
     *
     * @param \MDN\AdminBundle\Entity\Role $role
     */
    public function removeRole(\MDN\AdminBundle\Entity\Role $role)
    {
        $this->role->removeElement($role);
    }

    /**
     * Get role
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRole()
    {
        return $this->role;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getRoles()
    {
        $roles = array();
        
        foreach($this->role as $role) {
            $roles[] = $role->getCode();
        }
        
        return $roles;
    }
    
    /**
     * 
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return type
     */
    public function equals(UserInterface $user)
    {
        return $this->getId() == $user->getId();
    }
    
    /**
     * 
     */
    public function eraseCredentials()
    {
        return;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isAccountNonExpired()
    {
        return true;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isAccountNonLocked()
    {
        return true;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isCredentialsNonExpired()
    {
        return true;
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
        return ($this->deletedAt === NULL);
    }
    
    /**
     * @Assert\True(message="The password cannot match your username", groups={"Strict"})
     */
    public function isPasswordLegal()
    {
        return ($this->username !== $this->password);
    }
}
