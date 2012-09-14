<?php

require_once 'config.inc.php';

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Route;

class Index extends library\Front {
    
    public function __construct(EntityManager $em) 
    {
        $options = array();
        parent::__construct($em, $options);
    }
    
    protected function addCoreRoutes()
    {
        $kernel = $this->getKernel();
        
        // articles/contents
        $kernel->addRoute('contents', new Route('/articles/contents', 
                array('_controller' => 'coreBundleBE:ContentBlock:index')));
        $kernel->addRoute('content_new', new Route('/articles/contents/new', 
                array('_controller' => 'coreBundleBE:ContentBlock:create')));
        $kernel->addRoute('content_show', new Route('/articles/content/{id}', 
                array('_controller' => 'coreBundleBE:ContentBlock:show'), 
                array('id' => '\d+')));
        $kernel->addRoute('content_edit', new Route('/articles/content/{id}/edit', 
                array('_controller' => 'coreBundleBE:ContentBlock:edit'), 
                array('id' => '\d+')));

        // articles
        $kernel->addRoute('articles', new Route('/articles', 
                array('_controller' => 'coreBundleBE:Article:index')));
        $kernel->addRoute('article_new', new Route('/articles/new', 
                array('_controller' => 'coreBundleBE:Article:create')));
        $kernel->addRoute('article_show', new Route('/article/{alias}', 
                array('_controller' => 'coreBundleBE:Article:show')));
        $kernel->addRoute('article_edit', new Route('/article/{alias}/edit', 
                array('_controller' => 'coreBundleBE:Article:edit')));

        // pages
        $kernel->addRoute('pages', new Route('/pages', 
                array('_controller' => 'coreBundleBE:Page:index')));
        $kernel->addRoute('page_new', new Route('/pages/new', 
                array('_controller' => 'coreBundleBE:Page:create')));
        $kernel->addRoute('page_show', new Route('/page/{alias}', 
                array('_controller' => 'coreBundleBE:Page:show')));
        $kernel->addRoute('page_edit', new Route('/page/{alias}/edit', 
                array('_controller' => 'coreBundleBE:Page:edit')));
    }
    
    protected function addModulesRoutes()
    {
        $kernel = $this->getKernel();
        
        // blogs
        $kernel->addRoute('blogs', new Route('/blogs', 
                array('_controller' => 'blogBundleBE:Blog:index')));
        $kernel->addRoute('blog_show', new Route('/blog/{alias}', 
                array('_controller' => 'blogBundleBE:Blog:show')));
        $kernel->addRoute('blog_new', new Route('/blogs/new', 
                array('_controller' => 'blogBundleBE:Blog:create')));
        $kernel->addRoute('blog_edit', new Route('/blog/{alias}/edit', 
                array('_controller' => 'blogBundleBE:Blog:edit')));
    }
    
    protected function addAppRoutes()
    {
        $kernel = $this->getKernel();
        
        $kernel->addRoute('pauschalen', new Route('/pauschalen', 
                array('_controller' => 'appBundleBE:Pauschale:index')));
        $kernel->addRoute('pauschale', new Route('/pauschale/{alias}', 
                array('_controller' => 'appBundleBE:Pauschale:show')));
    }
    
    public function run()
    {
        // setup Routes
        $this->addAppRoutes();
        $this->addCoreRoutes();
        $this->addModulesRoutes();
                
        // send response to the browser
        //ob_start();
        $response = $this->getResponse(Request::createFromGlobals());
        //$removeText = ob_get_clean();// to remove unwanted line feeds before documents starts
        $response->headers->set('Content-Type', 'text/html');
        $response->setMaxAge(10);// configure the HTTP cache headers
        $response->send();
    }    
}

$index = new Index($em);
$index->run();

use bundles\core\models;

$kernel = $index->getKernel();
$em = $kernel->getEntityManager();
$articleRepository = $kernel->getBundleModelRepository('core', 'Article');
$nodeArticleRepository = $kernel->getBundleModelRepository('core', 'NodeArticle');
$contentRepository = $kernel->getBundleModelRepository('core', 'Content');
$tagRepository = $kernel->getBundleModelRepository('core', 'Tag');
//$taxonomyRepository = $kernel->getBundleModelRepository('core', 'Taxonomy');
//$taxItemRepository = $kernel->getBundleModelRepository('core', 'TaxonomyItem');

//$article = new models\Article();
//$article->setName('Zimmerpreise');
//$article->setAlias('zimmerpreise');
//$articleContent = new models\Content('Root');
//$articleContent->setUseTags(true);
//$articleContent->setUseTaxonomy(true);
//$articleContent->addTags($tagRepository->findByValue(array('Vermietung','Preise')));
//$article->setContent($articleContent);
//$em->persist($article);
//
$article = $articleRepository->findOneByAlias('zimmerpreise');
//$content = new models\Content('Text');
//$content->setParent($article->getContent());
//$content->setTitle('Zimmerpreise Sommer 2012');
//$content->setTitleHtmlTag('h1');
//$content->setValue('Die Preise sind derzeit so günstig wie kaum zuvor.');
//$em->persist($article);
//
//$content = new models\Content('Text');
//$content->setParent($article->getContent());
//$content->setUseI18n(true);
//$content->setTitle('Zimmerpreise Winter 2012/2013');
//$content->setTitleHtmlTag('h1');
//$content->setValue('Durch die WM sind starke Anstiege bei den Zimmerpreisen zu erwarten.');
//$em->persist($content);
//
//$contentI18n = new models\ContentI18n('en');
//$contentI18n->setTitle("Room prices Winter 2012/2013");
//$contentI18n->setValue("Trough the WM strong increases in room pricing could be expected.");
//$content->addLangI18n($contentI18n);
//$em->persist($contentI18n);
//$content->addLangI18n($contentI18n);

$nodeId = $article->getContent()->getNodeComment()->getId();
//
//$comment = new models\Comment();
//$comment->setAuthor('Manuel Binder');
//$comment->setAuthorEmail('lolovondambach@yahoo.com');
//$comment->setValue('Das hätte ich nie geglaubt. Ein Wahnsinn.');
//$article->getContent()->addComment($comment);
//
//$comment = new models\Comment();
//$comment->setAuthor('Wilfried Reiter');
//$comment->setAuthorEmail('wilfried@creativity4me.com');
//$comment->setValue('Gratuliere zu diesem wirklich informativen Artikel.');
//$article->getContent()->addComment($comment);



//$articleContents = $article->getContent()->getChildren();
//$articleContents->first()->addLangI18n($contentI18n);
//$article->getContent()->addMeta(new models\ContentMeta('email', 'wilfried@creativity4me.com'));

$em->flush();

//$articleModule = new \bundles\core\controller\ArticleSingle($kernel);
//echo $articleModule->generate('zimmerpreise');

//echo $article->getName() . '<br>';

//
//echo $article->getName() . '<br>';
//$articleContent = $article->getContent();
//foreach($articleContent->getChildren() as $content)
//{
//    echo $content->getTitle() . '<br>';
//}
//if ($article->getContent()->hasTags())
//{
//    foreach($article->getContent()->getTags() as $tag)
//    {
//        echo $tag->getValue() . ',';
//    }
//}

?>
