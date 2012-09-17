<?php
namespace bundle\core\models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="bundle\core\models\ArticleRepository")
 * @Table(name="tcm_articles", 
 *        indexes={@index(name="IDX_ALIAS", columns={"alias"})})
 */
class Article {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @Desc: primary Id for persistent storage
     * 
     */
    protected $id;
	
    /**
     * @ManyToOne(targetEntity="bundle\core\models\NodeArticle", inversedBy="articles")
     * 
     */
    protected $node;
	
    /**
     * @Column(type="boolean")
     * @Desc: <Article> has been published
     * 
     */
    protected $published = false;
    
    /**
     * @Column(type="boolean")
     * @Desc: <Article> should be available in multiple languages
     * 
     */
    protected $useI18n = false;
    
    /**
     * @Column(type="string")
     * @Desc: Displayed <Article> name
     * 
     */
    protected $name = '';
    
    /**
     * @Column(type="string")
     * @Desc: <Article> alias
     * 
     */
    protected $alias = '';
    
    /**
     * @OneToOne(targetEntity="bundle\core\models\Content", cascade="persist")
     * @JoinColumn(name="content_id", referencedColumnName="id")     
     * 
     */
    protected $content;

    /**
     * @OneToOne(targetEntity="bundle\core\models\Content", cascade="persist")
     * @JoinColumn(name="teaser_id", referencedColumnName="id")     
     * 
     */
    protected $teaser;

    /**
     * @OneToOne(targetEntity="bundle\core\models\Content", cascade="persist")
     * @JoinColumn(name="image_id", referencedColumnName="id")     
     * 
     */
    protected $image;


    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setNode($node)
    {
       $this->node = $node;
    }

    public function getNode()
    {
        return $this->node;
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

    public function setContent($content)
    {
       $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    } 

    public function setTeaser($teaser)
    {
       $this->teaser = $teaser;
    }

    public function getTeaser()
    {
        return $this->teaser;
    } 
    
    public function setImage($image)
    {
       $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    } 
}

class ArticleRepository extends EntityRepository {
    
    public function findByAliasOrId($mixed)
    {
        if (is_numeric($mixed)) $article = $this->find($mixed);
        else $article = $this->findOneByAlias($mixed);
        return $article;
    }
}
