<?php

namespace bundle\core;

use \bundle\core\models;

class CoreBundle extends library\Bundle {
    
    public function __construct()
    {
        $this->contentTypes = array(
            'Root' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentRoot',
                'widgetClass' => '\\bundle\\core\\elements\\ContentRootWidget')),
            'Text' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentText',
                'widgetClass' => '\\bundle\\core\\elements\\ContentTextWidget')),
            'Headline' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentHeadline',
                'widgetClass' => '\\bundle\\core\\elements\\ContentHeadlineWidget')),
            'Image' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentImage',
                'widgetClass' => '\\bundle\\core\\elements\\ContentImageWidget')),
            'Link' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentLink',
                'widgetClass' => '\\bundle\\core\\elements\\ContentLinkWidget')),
            'Gallery' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentGallery',
                'widgetClass' => '\\bundle\\core\\elements\\ContentGalleryWidget')),
            'Custom' => new ContentType(array(
                'elementClass' => '\\bundle\\core\\elements\\ContentCustom',
                'widgetClass' => '\\bundle\\core\\elements\\ContentCustomWidget')));
        $this->panelTypes = array(
            'ArticleFull' => new PanelType(array(
                'panelClass' => '\\bundle\\core\\controller\\ArticleFull',
                'valueClass' => '\\bundle\\core\\models\\Article')),
            'ArticleShortItem' => new PanelType(array(
                'panelClass' => '\\bundle\\core\\controller\\ArticleShortItem',
                'valueClass' => '\\bundle\\core\\models\\Article')),
            'ArticleTeaserItem' => new PanelType(array(
                'panelClass' => '\\bundle\\core\\controller\\ArticleTeaserItem',
                'valueClass' => '\\bundle\\core\\models\\Article')),
            'ArticleShortList' => new PanelType(array(
                'panelClass' => '\\bundle\\core\\controller\\ArticleShortList',
                'valueClass' => '\\bundle\\core\\models\\NodeArticle')),
            'ArticleTeaserList' => new PanelType(array(
                'panelClass' => '\\bundle\\core\\controller\\ArticleTeaserList',
                'valueClass' => '\\bundle\\core\\models\\NodeArticle')));
        $this->widgetTypes = array(
            'Article' => new WidgetType(array(
                'widgetClass' => '\\bundle\\core\\controller\\ArticleWidget',
                'valueClass' => '\\bundle\\core\\models\\Article')),
            'Taxonomy' => new WidgetType(array(
                'widgetClass' => '\\bundle\\core\\controller\\TaxonomyWidget',
                'valueClass' => '\\bundle\\core\\models\\Taxonomy')),
            'Page' => new WidgetType(array(
                'widgetClass' => '\\bundle\\core\\controller\\PageWidget',
                'valueClass' => '\\bundle\\core\\models\\Page')));
    }
}
