<?php
namespace bundle\core\models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="tcm_node_comments")
 */
class NodeComment {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @Desc: primary Id for persistent storage
     * 
     */
    protected $id;
	
    /**
     * @Column(type="string")
     * @Desc: embeding container type
     * 
     */
    protected $containerType;
    
    /**
     * @OneToMany(targetEntity="bundle\core\models\Comment", mappedBy="node", cascade={"persist"})
     * 
     */
    protected $comments;
    
	
    public function __construct($containerType)
    {
        $this->containerType = $containerType;
        $this->comments = new ArrayCollection();
    }
    
    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function addComment($value)
    {
       $this->comments[] = $value;
       $value->setNode($this);
    }

    public function hasComments()
    {
        return (0 != $this->comments->count());
    }
    
    public function getComments()
    {
        return $this->comments;
    }    
}

