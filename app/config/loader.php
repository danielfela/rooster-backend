<?php

$loader = new \Phalcon\Autoload\Loader();

//$eventsManager = new \Phalcon\Events\Manager();
/** @var ArrayObject $config */
/*$loader->(
    array(
        $config->application->modelsDir,
        $config->application->libraryDir,
    )
);*/
$loader->setNamespaces([
    'Controllers' => [$config->application->controllersDir],
    'Library' => [$config->application->libraryDir],
    'Model' => [$config->application->modelsDir],
]);
/*$eventsManager->attach(
    'loader:beforeCheckPath',
    function (
        \Phalcon\Events\Event $event,
        \Phalcon\Loader $loader
    ) {
        var_dump(is_file($loader->getCheckedPath()));
        var_dump($loader->getCheckedPath(), $loader->getfoundPath());
    }
);
$loader->setEventsManager($eventsManager);*/
$loader->register();
//var_dump($loader);



