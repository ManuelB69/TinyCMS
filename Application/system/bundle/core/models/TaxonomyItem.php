<?php
namespace bundle\core\models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="tcm_taxonomy_items",
 *        indexes={@index(name="IDX_NAME", columns={"name"})})
 */
class TaxonomyItem {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * 
     */
    protected $id;
	
    /**
     * @ManyToOne(targetEntity="bundle\core\models\Taxonomy", inversedBy="children")
     * 
     */
    protected $taxonomy;
	
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
    
    public function setTaxonomy($taxonomy)
    {
       $this->taxonomy = $taxonomy;
    }

    public function getTaxonomy()
    {
        return $this->taxonomy;
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

