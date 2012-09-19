<?php
namespace Tinycms\Bundle\CoreBundle\models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 * @Table(name="tcm_content_i18ns")
 *
 */
class ContentI18n {

    /**
     * @Id @ManyToOne(targetEntity="Tinycms\Bundle\CoreBundle\models\Content", inversedBy="i18ns")
     * @Desc: base content
     * 
     */
    protected $content;
	
    /**
     * @Id @Column(type="string", columnDefinition="CHAR(5) NOT NULL")
     * @Desc: used language
     * 
     */
    protected $lang = 'en';
    
    /**
     * @Column(type="string")
     * @Desc: user entered title text
     * 
     */
    protected $title = '';
    
    /**
     * @Column(type="text", nullable=true)
     * @Desc: User entered <Content> value
     * 
     */
    protected $value;
    
    
    public function __construct($lang)
    {
        $this->lang = $lang;
    }
    
    public function setContent($content)
    {
       $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setLang($value)
    {
        $this->lang = $value;
    }
    
    public function getLang()
    {
        return $this->lang;
    }

    public function setTitle($value)
    {
        $this->title = $value;
    }
    
    public function getTitle()
    {
        return $this->title;
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

