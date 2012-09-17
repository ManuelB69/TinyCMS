<?php
namespace bundle\core\models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="bundle\core\models\PageRepository")
 * @Table(name="tcm_pages", 
 *        indexes={@index(name="IDX_ALIAS", columns={"alias"})})
 */
class Page {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @Desc: primary Id for persistent storage
     * 
     */
    protected $id;
	
    /**
     * @Column(type="boolean")
     * @Desc: <Page> should be invisible
     * 
     */
    protected $invisible = false;
    
    /**
     * @Column(type="boolean")
     * @Desc: <Page> has been published
     * 
     */
    protected $published = false;
    
    /**
     * @Column(type="boolean")
     * @Desc: <Page> should be available in multiple languages
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
    protected $alias = '';
    
    /**
     * @Column(type="string")
     * 
     */
    protected $title = '';
    
    /**
     * @Column(type="text", nullable=true)
     * 
     */
    protected $description;
    
    /**
     * @OneToOne(targetEntity="bundle\core\models\NodeArticle", cascade="persist")
     * @JoinColumn(name="node_article_id", referencedColumnName="id")     
     * 
     */
    protected $nodeArticle;
    
	
    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setInvisible($value)
    {
        $this->invisible = $value;
    }
    
    public function getInvisible()
    {
        return $this->invisible;
    }

    public function setPublished($value)
    {
        $this->published = $value;
    }
    
    public function getPublished()
    {
        return $this->published;
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

    public function setAlias($value)
    {
        $this->alias = $value;
    }
    
    public function getAlias()
    {
        return $this->alias;
    }

    public function setTitle($value)
    {
        $this->title = $value;
    }
    
    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function setNodeArticle($nodeArticle)
    {
        $this->nodeArticle = $nodeArticle;
    }

    public function getNodeArticle()
    {
        return $this->nodeArticle;
    }
    
    public function addArticle($article, $section=null)
    {
        if (empty($section))
        {
            $this->getNodeArticle()->addArticle($article);
        }
        else
        {
            $nodeChildren = $this->getNodeArticle()->getChildren();
            foreach($nodeChildren as $nodeArticle)
            {
                if ($nodeArticle->getSection() === $section)
                {
                    $nodeArticle->addArticle($article);
                    return;
                }
            }
        }
    }  
}

class PageRepository extends EntityRepository {
    
    public function findByAliasOrId($mixed)
    {
        if (is_numeric($mixed)) $page = $this->find($mixed);
        else $page = $this->findOneByAlias($mixed);
        return $page;
    }
}
