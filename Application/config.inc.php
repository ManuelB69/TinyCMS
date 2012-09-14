<?php
// Fehlermeldungen einschalten, nur zur Sicherheit ;-)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Importiere die zentralen Doctrine-Klassen in den globalen Namespace
use Doctrine\ORM\EntityManager;

// Die Autoloader-Klasse von Doctrine laden
require 'libraries/Symfony/Component/ClassLoader/UniversalClassLoader.php';
$classLoader = new \Symfony\Component\ClassLoader\UniversalClassLoader();
$classLoader->registerNamespace('app', realpath(__DIR__));
$classLoader->registerNamespace('bundle', realpath(__DIR__ . '/system'));
$classLoader->registerNamespace('library', realpath(__DIR__ . '/system'));
$classLoader->registerNamespace('Doctrine', realpath(__DIR__ . '/libraries'));
$classLoader->registerNamespace('Symfony', realpath(__DIR__ . '/libraries'));
$classLoader->registerNamespace('proxies', realpath(__DIR__) . '/system');
$classLoader->registerPrefix('Twig_', realpath(__DIR__ . '/libraries'));
$classLoader->register();

// Beginne mit der Konfiguration
$config = new \Doctrine\ORM\Configuration();
// Wir verwenden die neue Annotations-Syntax für die Modelle (Alternativen: yaml und xml-Dateien)
$driverImpl = $config->newDefaultAnnotationDriver(array(
                        __DIR__ . "/system/bundles"));
$config->setMetadataDriverImpl($driverImpl);

// Teile Doctrine mit, wo es die Proxy-Klassen ablegen soll
$config->setProxyDir(__DIR__ . '/system/proxies');
$config->setProxyNamespace('proxies');

// Wenn in PHP die Erweiterung APC (http://php.net/manual/de/book.apc.php)
// installiert ist, kann der Doctrine-Cache sie verwenden.
// Doctrine nutzt Caching sehr agressiv, wenn möglich also einschalten!
/*
$cache = new \Doctrine\Common\Cache\ApcCache();
$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);
$config->setResultCacheImpl($cache);
*/

// Die eigentliche Datenbankverbindung wird in Form eines Arrays angelegt,
// wobei die Schlüssel wie beim Erzeugen einer PDO-Instanz heißen. Das Array
// entspricht also:
// new PDO('mysql:dbname=doctrine2_test;host=localhost', 'root', '');
$connectionOptions = array(
    'driver' => 'pdo_mysql',
    'dbname' => 'tinycms',
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
);

// Instanz von Doctrine\ORM\EntityManager, dem zentralen Objekt des ORM von Doctrine.
// Über dieses Objekt werden alle Datenbank-Operationen abgewickelt.
$em = EntityManager::create($connectionOptions, $config);

// Mysql defaultmässig auf UTF8 konfigurieren
$em->getEventManager()->addEventSubscriber(new \Doctrine\DBAL\Event\Listeners\MysqlSessionInit('utf8', 'utf8_unicode_ci')); 
?>