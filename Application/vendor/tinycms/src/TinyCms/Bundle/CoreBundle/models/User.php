<?php
namespace Tinycms\Bundle\CoreBundle\models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="tcm_users", 
 *        indexes={@index(name="IDX_EMAIL", columns={"email"}),
 *                 @index(name="IDX_USERNAME", columns={"username"})})
 * 
 */
class User {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @Desc: primary Id for persistent storage
     * 
     */
    protected $id;
	
    /**
     * @Column(type="boolean")
     * @Desc: <User> has been disabled
     * 
     */
    protected $disabled = false;
    
    /**
     * @Column(type="string")
     * 
     */
    protected $username = '';
    
    /**
     * @Column(type="string")
     * 
     */
    protected $firstname = '';
    
    /**
     * @Column(type="string")
     * 
     */
    protected $lastname = '';
    
    /**
     * @Column(type="string")
     * 
     */
    protected $email = '';
    
    /**
     * @Column(type="string")
     * 
     */
    protected $password = '';
    
    /**
     * @Column(type="string")
     * 
     */
    protected $description;
    
	
    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setDisabled($value)
    {
        $this->disabled = $value;
    }
    
    public function getDisabled()
    {
        return $this->disabled;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }
    
    public function getUsername()
    {
        return $this->username;
    }

    public function setFirstname($value)
    {
        $this->firstname = $value;
    }
    
    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setLastname($value)
    {
        $this->lastname = $value;
    }
    
    public function getLastname()
    {
        return $this->lastname;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }
    
    public function getPassword()
    {
        return $this->password;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }
    
    public function getEmail()
    {
        return $this->email;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

}

