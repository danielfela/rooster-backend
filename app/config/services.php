<?php

/**
 * @var $config
 */

use Phalcon\Di\FactoryDefault;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;

$di = new FactoryDefault();
/**
 * Models manager
 */
$di->set('modelsManager', function () {
    return new \Phalcon\Mvc\Model\Manager();
});

$di->set("dispatcher", function () {
    $dispatcher = new \Phalcon\Mvc\Dispatcher();
    $dispatcher->setDefaultNamespace("Controllers");

    return $dispatcher;
});
/**
 * Sets the view component
 */
$di->setShared('view', function () use ($config) {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () use ($config, $di) {
    $url = new Phalcon\Mvc\Url();
    $url->setDi($di);
    $url->setBaseUri($config->application->baseUri);
    return $url;
});

$di->setShared('view', function () use ($config) {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

/**
 * Crypt service
 */
$di->set('tag', function () use ($config, $di) {
    $tag = new \Phalcon\Tag();
    $tag->setDi($di);
    return $tag;
}, true);

/**
 * JWT service
 */
/*$di->setShared('jwt', function () {
    return new JWT();
});*/

/**
 * tokenConfig
 */
/*$di->setShared('tokenConfig', function () use ($config) {
    return $config->authentication->toArray();
});*/

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($config) {
    $dbConfig = $config->database->toArray();
    $adapter  = $dbConfig['adapter'];
    unset($dbConfig['adapter']);

    $dbConfig['options'] = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
    ];
    $class               = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

    $connection = new $class($dbConfig);
    $connection->setNestedTransactionsWithSavepoints(true);


    return $connection;
});

$di->setShared('cache', function () {
    return new Library\Cache\Adapter();
});

$di->setShared('translate', function () {
    return new Library\Translation\Adapter();
});

$di->set('helper', new \Library\Support\HelperFactory());

$di->setShared('request', new  \Library\Http\Request());
$di->setShared('response', new  \Library\Http\Response());

$di->set(
    'session',
    function () {
        $maxlifetime = 0;
        session_name(SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => $maxlifetime,
            'path'     => '/',
            'domain'   => '.daniel-fela.pl',
            'secure'   => false,
            'httponly' => false,
            'samesite' => 'lax'
        ]);

        $session = new Manager();
        $files   = new Stream(['savePath' => '/tmp',]);

        $session
            ->setId('test')//REMOVE BEFORE SESSION RUN!!!!!!!!!!!
            ->setAdapter($files)
            ->start();

        $session->set('discord-request-count', 0);

        return $session;
    }
);

$di->set(
    'modelsCache',
    function () {
        $serializerFactory = new \Phalcon\Storage\SerializerFactory();
        $adapterFactory    = new \Phalcon\Cache\AdapterFactory($serializerFactory);

        $options = [
            'defaultSerializer' => 'Php',
            'lifetime'          => 7200
        ];

        $adapter = $adapterFactory->newInstance('apcu', $options);
        $cache   = new \Phalcon\Cache\Cache($adapter);
        //$cache->clear();
        return $cache;
    }
);

$di->setShared('instance', function () {
    return new Library\Instance\Instance();
});

