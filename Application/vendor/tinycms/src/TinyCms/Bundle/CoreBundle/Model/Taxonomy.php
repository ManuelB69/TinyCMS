<?php
namespace TinyCms\Bundle\CoreBundle\models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="tcm_taxonomy",
 *        indexes={@index(name="IDX_NAME", columns={"name"})})
 */
class Taxonomy {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * 
     */
    protected $id;
	
    /**
     * @ManyToOne(targetEntity="TinyCms\Bundle\CoreBundle\models\Taxonomy", inversedBy="children")
     * @Desc: parent for <Taxonomy> trees
     * 
     */
    protected $parent;
	
    /**
     * @OneToMany(targetEntity="TinyCms\Bundle\CoreBundle\models\Taxonomy", mappedBy="parent")
     * @Desc: children for <Taxonomy> trees
     *   
     */
    protected $children;    
    
    /**
     * @OneToMany(targetEntity="TinyCms\Bundle\CoreBundle\models\TaxonomyItem", mappedBy="taxonomy")
     *   
     */
    protected $items;
    
    /**
     * @Column(type="boolean")
     * @Desc: <Taxonomy> should be available for multiple languages
     * 
     */
    protected $useI18n = false;
    
    /**
     * @Column(type="string")
     * 
     */
    protected $name = '';
    
    /**
     * @Column(type="string")
     * 
     */
    protected $value = '';
    
	
    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setParent($parent)
    {
       $parent->children[] = $this;
       $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }    
  
    public function addItem($item)
    {
       $this->items[] = $item;
       $item->setTaxonomy($this);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setUseI18n($value)
    {
        $this->useI18n = $value;
    }
    
    public function getUseI18n()
    {
        return $this->useI18n;
    }
    
    public function setName($value)
    {
        $this->name = $value;
    }
    
    public function getName()
    {
        return $this->name;
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

