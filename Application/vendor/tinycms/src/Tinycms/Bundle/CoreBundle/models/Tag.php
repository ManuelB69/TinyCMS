<?php
namespace Tinycms\Bundle\CoreBundle\models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 * @Table(name="tcm_tags", 
 *        indexes={@index(name="IDX_VALUE", columns={"value"})})
 */
class Tag {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @Desc: primary Id for persistent storage
     * 
     */
    protected $id;
	
    /**
     * @Column(type="string")
     * 
     */
    protected $value = '';
    
	
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    public function getValue()
    {
        return $this->value;
    }
}

