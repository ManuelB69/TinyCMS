<?php
namespace Tinycms\Bundle\CoreBundle\models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="tcm_comments")
 */
class Comment {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @Desc: primary Id for persistent storage
     * 
     */
    protected $id;
	
    /**
     * @Column(type="integer")
     * @GeneratedValue
     * 
     */
    
    /**
     * @ManyToOne(targetEntity="Tinycms\Bundle\CoreBundle\models\NodeComment", inversedBy="comments")
     * 
     */
    protected $node;
	
    /**
     * @ManyToOne(targetEntity="Tinycms\Bundle\CoreBundle\models\Comment", inversedBy="children")
     * @Desc: parent for <Comment> trees
     * 
     */
    protected $parent;
	
    /**
     * @OneToMany(targetEntity="Tinycms\Bundle\CoreBundle\models\Comment", mappedBy="parent", cascade={"persist"})
     * @Desc: children for <Comment> trees
     *   
     */
    protected $children;    
    
    /**
     * @OneToMany(targetEntity="Tinycms\Bundle\CoreBundle\models\CommentMeta", mappedBy="comment", cascade={"persist"})
     * @Desc: meta data for <Comment>
     *   
     */
    protected $metas;    
    
    /**
     * @Column(type="boolean")
     * @Desc: <Comment> has meta data assigned
     * 
     */
    protected $useMeta = false;
    
    /**
     * @Column(type="string")
     * 
     */
    protected $author = '';
    
    /**
     * @Column(type="string")
     * 
     */
    protected $authorEmail = '';
    
    /**
     * @Column(type="string")
     * 
     */
    protected $authorUrl = '';
    
    /**
     * @Column(type="string")
     * 
     */
    protected $authorIP = '';
    
    /**
     * @Column(type="text", nullable=true)
     * @Desc: User entered <Comment> value
     * 
     */
    protected $value;
    
    
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->metas = new ArrayCollection();
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
    
    public function setNode($node)
    {
       $this->node = $node;
    }

    public function getNode()
    {
        return $this->node;
    }    
    
    public function setUseMeta($value)
    {
        $this->useMeta = $value;
    }
    
    public function getUseMeta()
    {
        return $this->useMeta;
    }

    public function addMeta($meta)
    {
        if ($this->getUseMeta())
        {
            $meta->setComment($this);
            $this->metas[] = $meta;
        }
    }
    
    public function hasMetas()
    {
        return ($this->getUseMeta() && 0 != $this->metas->count());
    }
    
    public function getMetas()
    {
        return $this->metas;
    }
  
    public function setAuthor($value)
    {
        $this->author = $value;
    }
    
    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthorEmail($value)
    {
        $this->authorEmail = $value;
    }
    
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    public function setAuthorUrl($value)
    {
        $this->authorUrl = $value;
    }
    
    public function getAuthorUrl()
    {
        return $this->authorUrl;
    }

    public function setAuthorIP($value)
    {
        $this->authorIP = $value;
    }
    
    public function getAuthorIP()
    {
        return $this->authorIP;
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

