<?php
namespace bundle\viewitem\models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 * @Table(name="view_items")
 */
class ViewItem {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     * @Desc: primary Id for persistent storage
     */
    public $id;
	
    /**
     * @Column(type="string")
     * @Desc: label to deal with this View
     * 
     */
    public $label;
    
    /**
     * @Column(type="string")
     * @Desc: company whose had created this view
     * 
     */
    public $vendor;
    
    /**
      * @Column(type="string")
      * @Desc: can be DB table name or other type name
      * 
      */
    public $itemTypeClass;
    
    /**
      * @Column(type="string", nullable=true)
      * @Desc: if item fields exist for different languages
      *   give postfix for current language (ie "-{{lang}})
      * 
      */
    public $itemLangPostfix;
    
    /**
     * @Column(type="boolean")
     * @Desc: ViewItem should be available for multiple languages
     * 
     */
    public $useI18N = false;
    
    /**
     * @Column(type="string", nullable=true)
     * @Desc: Language fallback if item isn't available for the current user language
     * 
     */
    public $langFallback;
    
    /**
     * @Column(type="string", nullable=true)
     * @Desc: CSS Id 
     * 
     */
    public $cssId;
	
    /**
     * @Column(type="string", nullable=true)
     * @Desc: one or multiple CSS classes
     * 
     */
    public $cssClass;
    
    /**
     * @Column(type="string", nullable=true)
     * @Desc: html output template
     * 
     */
    public $template;
    
    /**
     * @Column(type="string", nullable=true)
     * @Desc: User entered title text
     * 
     */
    public $title;                  
    
    /**
     * @Column(type="string", nullable=true)
     * @Desc: <h1>,<h2>..<hn>
     * 
     */
    public $titleHtmlTag;
    
    /**
     * @OneToMany(targetEntity="ViewItemField", mappedBy="view")
     * @Desc: <ViewField>s array
     * 
     */
    public $fields;

    protected function compileTitle($itemData)
    {
        $output = '';
        if($this->titleHtmlTag) $output.= '<'.$this->titleHtmlTag.'>';
        $output.= $this->title;
        if($this->titleHtmlTag) $output.= '</'.$this->titleHtmlTag.'>';
        return $output;
    }
	
    protected function compileField($field, $itemFieldData)
    {
    }

    protected function compileStaticField($field)
    {
    }

    protected function compileBody($itemData)
    {
        // compiling view properties
        $viewData = array();
        $viewData['title']['raw'] = $this->title;
        $viewData['title']['value'] = $this->compileTitle($itemData);

        // compiling view fields
        $viewFieldsData = array();
        $viewData['fields'] = &$viewFieldsData;
        
        foreach ($this->fields as $field)
        {
            if (isset($field->itemField))
            {
                $itemFieldData = &$itemData[$field->itemField];
                $viewFieldsData[$field->label]['raw'] = $itemFieldData;
                $viewFieldsData[$field->label]['value'] = $this->compileField($field, $itemFieldData);
            }
            elseif (isset($field->staticProperty))
            {
                $viewFieldsData[$field->label]['raw'] = $field->staticNodes;
                $viewFieldsData[$field->label]['value'] = $this->compileStaticField($field);
            }
        }
        return $viewData;
    }
}

?>