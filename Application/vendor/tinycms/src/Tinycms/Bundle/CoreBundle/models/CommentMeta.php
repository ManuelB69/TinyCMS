<?php
namespace bundle\core\models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 * @Table(name="tcm_comment_metas")
 *
 */
class CommentMeta {

    /**
     * @Id @ManyToOne(targetEntity="bundle\core\models\Comment", inversedBy="metas")
     * @Desc: base comment
     * 
     */
    protected $comment;
	
    /**
     * @Id @Column(type="string")
     * @Desc: <CommentMeta> key
     * 
     */
    protected $metaKey = '';
    
    /**
     * @Column(type="string")
     * @Desc: <CommentMeta> value
     * 
     */
    protected $value;
    
    
    public function __construct($key, $value=null)
    {
        $this->metaKey = $key;
        $this->value = $value;
    }
    
    public function setComment($comment)
    {
       $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
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

