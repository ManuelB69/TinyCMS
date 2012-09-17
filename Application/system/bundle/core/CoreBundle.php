<?php

namespace bundle\core;

class CoreBundle extends library\Bundle {
    
    public function __construct()
    {
        $bundleNamespace = $this->getNamespace();
        $this->contentTypes = array(
            'Root' => new ContentType(array(
                'elementClass' => $bundleNamespace . '\\elements\\ContentRoot',
                'widgetClass' => $bundleNamespace . '\\elements\\ContentRootWidget')),
            'Text' => new ContentType(array(
                'elementClass' => $bundleNamespace . '\\elements\\ContentText',
                'widgetClass' => $bundleNamespace . '\\elements\\ContentTextWidget')),
            'Headline' => new ContentType(array(
                'elementClass' => $bundleNamespace . '\\elements\\ContentHeadline',
                'widgetClass' => $bundleNamespace . '\\elements\\ContentHeadlineWidget')),
            'Image' => new ContentType(array(
                'elementClass' => $bundleNamespace . '\\elements\\ContentImage',
                'widgetClass' => $bundleNamespace . '\\elements\\ContentImageWidget')),
            'Link' => new ContentType(array(
                'elementClass' => $bundleNamespace . '\\elements\\ContentLink',
                'widgetClass' => $bundleNamespace . '\\elements\\ContentLinkWidget')),
            'Gallery' => new ContentType(array(
                'elementClass' => $bundleNamespace . '\\elements\\ContentGallery',
                'widgetClass' => $bundleNamespace . '\\elements\\ContentGalleryWidget')),
            'Custom' => new ContentType(array(
                'elementClass' => $bundleNamespace . '\\elements\\ContentCustom',
                'widgetClass' => $bundleNamespace . '\\elements\\ContentCustomWidget')));
        $this->panelTypes = array(
            'ArticleFull' => new PanelType(array(
                'panelClass' => $bundleNamespace . '\\controller\\ArticleFull',
                'valueClass' => $bundleNamespace . '\\models\\Article')),
            'ArticleShortItem' => new PanelType(array(
                'panelClass' => $bundleNamespace . '\\controller\\ArticleShortItem',
                'valueClass' => $bundleNamespace . '\\models\\Article')),
            'ArticleTeaserItem' => new PanelType(array(
                'panelClass' => $bundleNamespace . '\\controller\\ArticleTeaserItem',
                'valueClass' => $bundleNamespace . '\\models\\Article')),
            'ArticleShortList' => new PanelType(array(
                'panelClass' => $bundleNamespace . '\\controller\\ArticleShortList',
                'valueClass' => $bundleNamespace . '\\models\\NodeArticle')),
            'ArticleTeaserList' => new PanelType(array(
                'panelClass' => $bundleNamespace . '\\controller\\ArticleTeaserList',
                'valueClass' => $bundleNamespace . '\\models\\NodeArticle')));
        $this->widgetTypes = array(
            'Article' => new WidgetType(array(
                'widgetClass' => $bundleNamespace . '\\controller\\ArticleWidget',
                'valueClass' => $bundleNamespace . '\\models\\Article')),
            'Taxonomy' => new WidgetType(array(
                'widgetClass' => $bundleNamespace . '\\controller\\TaxonomyWidget',
                'valueClass' => $bundleNamespace . '\\models\\Taxonomy')),
            'Page' => new WidgetType(array(
                'widgetClass' => $bundleNamespace . '\\controller\\PageWidget',
                'valueClass' => $bundleNamespace . '\\models\\Page')));
    }
}
