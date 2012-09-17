<?php
namespace bundle\viewitem\models;

//use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 * @Table(name="view_item_fields")
 */
class ViewItemField {

   /**
    * @Id @Column(type="integer")
    * @GeneratedValue
    * @Desc: primary Id for persistent storage
    * 
    */
    public $id;
	
   /**
    * @ManyToOne(targetEntity="ViewItem", inversedBy="fields")
    * @Desc: primary Id for persistent storage
    * 
    */
    public $view;
	
   /**
    * @Column(type="string")
    * @Desc: label to deal with this ViewField
    * 
    */
    public $label;
    
   /**
    * @Column(type="boolean")
    * @Desc: Define if field is visible
    * 
    */
    public $invisible = false;
    
   /**
    * @Column(type="string", nullable=true)
    * @Desc: User entered Title text
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
    * @Column(type="integer", nullable=true)
    * @Desc: if item data is an image 
    *   display width and height can be given
    * 
    */
    public $imgWidth;

   /**
    * @Column(type="integer", nullable=true)
    * @Desc: if item data is an image 
    *   display width and height can be given
    * 
    */
    public $imgHeight;
    
   /**
    * @Column(type="string", nullable=true)
    * @Desc: if item data is an number or text it can be 
    *    decorated with additional text (ie "EUR {{value}},-")
    * 
    */
    public $decoration;
    
   /**
    * @Column(type="string", nullable=true)
    * @Desc: CSS Id and one or multiple css classes
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
    * @Desc: name of the item fields used in this ViewItem
    * 
    */
    public $itemField;

   /**
    * @Column(type="string", nullable=true)
    * @Desc: name of the item group used for this ViewItem
    * 
    */
    public $itemGroup;
    
   /**
    * @Column(type="string", nullable=true)
    * @Desc: if item data is an object it can become 
    *    its own <View> to display it
    * 
    */
    public $itemView;
    
   /**
    * @Column(type="string", nullable=true)
    * @Desc: if <View> is an input form each field can use 
    *    a widget for inputting data
    * 
    */
    public $widget;
    
   /**
    * @Column(type="string", nullable=true)
    * @Desc: output template for converting item data to <html>
    * 
    */
    public $template;
    
   /**
    * @Column(type="string", nullable=true)
    * @Desc: <ViewField> can be grouped into various group types: 
    *    List, Table, Object, ObjectList,...
    */
    public $groupType;
    
   /**
    * @Column(type="integer", nullable=true)
    * @Desc: group row count for List, Table, ObjectList
    */
    public $groupRowCnt;

   /**
    * @Column(type="integer", nullable=true)
    * @Desc: group column count for Table
    */
    public $groupColumnCnt;

   /**
    * @Column(type="string", nullable=true)
    * @Desc: fields parent group name
    */
    public $groupLabel;

   /**
	 * @Column(type="integer", nullable=true)
	 * @Column(type="integer", nullable=true)
	 */
    public $groupRow;

   /**
    * @Column(type="integer", nullable=true)
    * @Desc: fields parent group column
    */
    public $groupColumn;

   /**
    * @Column(type="string", nullable=true)
    * @Desc: fields parent group object field
    */
    public $groupField;
   
    public function compile($data)
    {
    }    
}

