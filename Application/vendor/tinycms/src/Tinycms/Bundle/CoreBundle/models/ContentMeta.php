<?php
namespace Tinycms\Bundle\CoreBundle\models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 * @Table(name="tcm_content_metas",
 *        indexes={@index(name="IDX_CONTENT_METAKEY", columns={"content_id","metaKey"})})
 *
 */
class ContentMeta {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @Desc: primary Id for persistent storage
     * 
     */
    protected $id;
    
    /**
     * @ManyToOne(targetEntity="Tinycms\Bundle\CoreBundle\models\Content", inversedBy="metas")
     * @Desc: base content
     * 
     */
    protected $content;
	
    /**
     * @Column(type="string")
     * @Desc: <ContentMeta> key
     * 
     */
    protected $metaKey = '';
    
    /**
     * @Column(type="text", nullable=true)
     * @Desc: <ContentMeta> value
     * 
     */
    protected $value;
    
    
    public function __construct($key, $value=null)
    {
        $this->metaKey = $key;
        $this->value = $value;
    }
    
    public function setContent($content)
    {
       $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setMetaKey($key)
    {
        $this->metaKey = $key;
    }
    
    public function getMetaKey()
    {
        return $this->metaKey;
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

