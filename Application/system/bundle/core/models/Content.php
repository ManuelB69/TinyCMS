<?php
namespace bundle\core\models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="tcm_contents")
 */
class Content {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @Desc: primary Id for persistent storage
     * 
     */
    protected $id;
	
    /**
     * @ManyToOne(targetEntity="bundle\core\models\Content", inversedBy="children")
     * @Desc: parent for <Content> trees
     * 
     */
    protected $parent;
	
    /**
     * @OneToMany(targetEntity="bundle\core\models\Content", mappedBy="parent", cascade={"persist"})
     * @Desc: children for <Content> trees
     *   
     */
    protected $children;    
    
    /**
     * @OneToMany(targetEntity="bundle\core\models\ContentI18n", mappedBy="content", cascade={"persist"})
     * @Desc: langI18n variants for <Content>
     *   
     */
    protected $i18ns;    
    
    /**
     * @OneToMany(targetEntity="bundle\core\models\ContentMeta", mappedBy="content", cascade={"persist"})
     * @Desc: meta data for <Content>
     *   
     */
    protected $metas;    
    
    /**
     * @Column(type="string")
     * @Desc: <Content> type
     * 
     */
    protected $type = 'Text';
    
    /**
     * @Column(type="string")
     * 
     */
    protected $customType = '';
    
    /**
     * @Column(type="string")
     * @Desc: <Content> field name
     * 
     */
    protected $name = '';
    
    /**
     * @Column(type="boolean")
     * @Desc: <Content> should be invisible
     * 
     */
    protected $invisible = false;
    
    /**
     * @Column(type="boolean")
     * @Desc: <Content> should be available for multiple langI18ns
     * 
     */
    protected $useI18n = false;
    
    /**
     * @Column(type="boolean")
     * @Desc: <Content> has meta data assigned
     * 
     */
    protected $useMeta = false;
    
    /**
     * @Column(type="boolean")
     * @Desc: displaying <Content> element depends on a filter
     * 
     */
    protected $useFilter = false;
    
    /**
     * @Column(type="boolean")
     * @Desc: Tags can be used for this <Content>
     * 
     */
    protected $useTags = false;
    
    /**
     * @Column(type="boolean")
     * @Desc: Tags can be used for this <Content>
     * 
     */
    protected $useTaxonomy = false;
    
    /**
     * @Column(type="boolean")
     * @Desc: Visitors are able to comment the <Article>
     * 
     */
    protected $useComment = false;
    
    /**
     * @ManyToMany(targetEntity="Taxonomy")
     * @JoinTable(name="tcm_contents_taxonomy_items",
     *      joinColumns={@JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="taxonomy_item_id", referencedColumnName="id")})
     */
    protected $taxonomyItems;

    /**
     * @ManyToMany(targetEntity="Tag")
     * @JoinTable(name="tcm_contents_tags",
     *      joinColumns={@JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="tag_id", referencedColumnName="id")})
     */
    protected $tags;
    
    /**
     * @OneToOne(targetEntity="bundle\core\models\NodeComment", cascade="persist")
     * @JoinColumn(name="node_comment_id", referencedColumnName="id")     
     * 
     */
    protected $nodeComment;
    
    /**
     * @Column(type="string")
     * @Desc: User entered title text
     * 
     */
    protected $title = '';
    
    /**
     * @Column(type="string", columnDefinition="CHAR(2) NOT NULL")
     * @Desc: <h1>,<h2>..<hn>
     * 
     */
    protected $titleHtmlTag = '';
    
    /**
     * @Column(type="text", nullable=true)
     * @Desc: User entered <Content> value
     * 
     */
    protected $value;
    
    /**
     * @Column(type="string")
     * @Desc: template for rendering
     * 
     */
    protected $template = '';
    
    
    public function __construct($type)
    {
        $this->type = $type;
        $this->i18ns = new ArrayCollection();
        $this->metas = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->taxonomyItems = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
    
    public function setType($value)
    {
        $this->type = $value;
    }
    
    public function getType()
    {
        return $this->type;
    }

    public function setCustomType($value)
    {
        $this->customType = $value;
    }
    
    public function getCustomType()
    {
        return $this->customType;
    }

    public function setName($value)
    {
        $this->name = $value;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setInvisible($value)
    {
        $this->invisible = $value;
    }
    
    public function getInvisible()
    {
        return $this->invisible;
    }

    public function setUseFilter($value)
    {
        $this->useFilter = $value;
    }
    
    public function getUseFilter()
    {
        return $this->useFilter;
    }

    public function setUseTaxonomy($value)
    {
        $this->useTaxonomy = $value;
    }
    
    public function getUseTaxonomy()
    {
        return $this->useTaxonomy;
    }

    public function addTaxonomyItem($item)
    {
        if ($this->getUseTaxonomy())
        {
            $this->taxonomyItems[] = $item;
        }
    }
    
    public function hasTaxonomyItems()
    {
        return ($this->getUseTaxonomy() && 0 != $this->taxonomyItems->count());
    }
    
    public function getTaxonomyItems()
    {
        return $this->taxonomyItems;
    }
  
    public function setUseTags($value)
    {
        $this->useTags = $value;
    }
    
    public function getUseTags()
    {
        return $this->useTags;
    }

    public function addTag($tag)
    {
        if ($this->getUseTags())
        {
            if (is_array($tag)) $this->addTags($tag);
            else $this->tags[] = $tag;
        }
    }
    
    public function addTags($tags)
    {
        foreach ($tags as $tag)
        {
            $this->addTag($tag);
        }
    }
    
    public function hasTags()
    {
        return ($this->getUseTags() && 0 != $this->tags->count());
    }
    
    public function getTags()
    {
        return $this->tags;
    }
  
    public function setUseI18n($value)
    {
        $this->useI18n = $value;
    }
    
    public function getUseI18n()
    {
        return $this->useI18n;
    }

    public function addLangI18n($langI18n)
    {
        if ($this->getUseI18n())
        {
            $langI18n->setContent($this);
            $this->i18ns[] = $langI18n;
        }
    }
    
    public function hasLangI18ns()
    {
        return ($this->getUseI18n() && 0 != $this->i18ns->count());
    }
    
    public function getLangI18ns()
    {
        return $this->i18ns;
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
            $meta->setContent($this);
            $this->metas[] = $meta;
        }
    }
    
    public function hasMetas()
    {
        return ($this->getUseMeta() && 0 != $this->metas->count());
    }
    
    public function getMeta($metaKey)
    {
        if ($this->hasMetas()) 
        {
            foreach ($this->getMetas() as $meta)
            {
                if ($meta->metaKey === $metaKey)
                {
                    return $meta->value;
                }
            }
        }
        return null;
    }    
    
    public function getMetas()
    {
        return $this->metas;
    }
  
    public function setUseComment($value)
    {
        $this->useComment = $value;
    }
    
    public function getUseComment()
    {
        return $this->useComment;
    }

    public function setNodeComment($nodeComment)
    {
        $this->nodeComment = $nodeComment;
    }

    public function getNodeComment()
    {
        return $this->nodeComment;
    }    
    
    public function addComment($comment)
    {
        if ($this->getUseComment())
        {
            $this->getNodeComment()->addComment($comment);
        }
    }
    
    public function hasComments()
    {
        if ($this->getUseComment())
        {
            $nodeComment = $this->getNodeComment();
            if ($nodeComment)
            {
                return $nodeComment->hasComments();
            }
        }
        return false;
    }
    
    public function getComments()
    {
        return $this->getNodeComment()->getComments();
    }    
    
    public function setTitle($value)
    {
        $this->title = $value;
    }
    
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitleHtmlTag($value)
    {
        $this->titleHtmlTag = $value;
    }
    
    public function getTitleHtmlTag()
    {
        return $this->titleHtmlTag;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
    
    public function getValue()
    {
        return $this->value;
    }

    public function setTemplate($value)
    {
        $this->template = $value;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
}

