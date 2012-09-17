<?php
namespace bundle\core\models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="tcm_node_articles")
 */
class NodeArticle {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @Desc: primary Id for persistent storage
     * 
     */
    protected $id;
	
    /**
     * @ManyToOne(targetEntity="bundle\core\models\NodeArticle", inversedBy="children")
     * @Desc: parent for <NodeArticle> trees
     * 
     */
    protected $parent;

    /**
     * @OneToMany(targetEntity="bundle\core\models\NodeArticle", mappedBy="parent", cascade={"persist"})
     * @Desc: children for <NodeArticle> trees
     *   
     */
    protected $children;    
    
    /**
     * @Column(type="string")
     * @Desc: embeding container type
     * 
     */
    protected $containerType;
    
    /**
     * @Column(type="string")
     * @Desc: section to link the article
     * 
     */
    protected $section = '';
    
    /**
     * @OneToMany(targetEntity="bundle\core\models\Article", mappedBy="node", cascade={"persist"})
     * 
     */
    protected $articles;
    
	
    public function __construct($containerType, $section)
    {
        $this->children = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->containerType = $containerType;
        $this->section = $section;
    }
    
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
    
    public function setSection($value)
    {
        $this->section = $value;
    }
    
    public function getSection()
    {
        return $this->section;
    }

    public function addArticle($value)
    {
       $this->articles[] = $value;
       $value->setNode($this);
    }

    public function getArticles()
    {
        return $this->articles;
    }    
}

