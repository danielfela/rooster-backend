<?php
/**
 * Starting the application
 * Assign service locator to the application
 *
 * On finish or error session is stored
 *
 * @var $di
 * @var \Library\Instance\Instance $user
 */
$app = new \Phalcon\Mvc\Micro($di);
use Controllers\IndexController as IndexController;
use Phalcon\Mvc\Micro\Collection as MicroCollection;

/*$i = new MicroCollection();
var_dump(IndexController::class);
$i->setHandler(IndexController::class, true);
$i->setLazy(true);
$i->setPrefix('/index');
$i->get('/', 'index');
$app->mount($i);*/


$user = $di->getShared('instance');


$i3 = new MicroCollection();
$i3->setHandler(IndexController::class, true);
$i3->setLazy(true);
$i3->setPrefix('/index');
$i3->get('/', 'index');
$i3->get('/index', 'index');
$app->mount($i3);

$i3 = new MicroCollection();
$i3->setHandler(IndexController::class, true);
$i3->setLazy(true);
$i3->setPrefix('/');
$i3->get('', 'index');
$app->mount($i3);

// Users handler
$crafters = new MicroCollection();
$crafters->setHandler(\Controllers\CraftersController::class, true);
$crafters->setLazy(true);
$crafters->setPrefix('/crafters');
//$crafters->get('/get/{id}', 'get');
$crafters->get('/add/{payload}', 'add');
$app->mount($crafters);

// Users handler
$crafters = new MicroCollection();
$crafters->setHandler(\Controllers\CraftersController::class, true);
$crafters->setLazy(true);
$crafters->setPrefix('/crafters');
//$crafters->get('/get/{id}', 'get');
$crafters->get('/add', 'showAddForm');

$app->mount($crafters);

if($user->isUser()) {
    // Users handler
    $builds = new MicroCollection();
    $builds->setHandler(\Controllers\BuildsController::class, true);
    $builds->setLazy(true);
    $builds->setPrefix('/builds');
    $builds->post('/save', 'save');
    $builds->get('/get', 'get');
    $app->mount($builds);
}

$auth = new MicroCollection();
$auth->setHandler(\Controllers\AuthController::class, true);
$auth->setLazy(true);
$auth->setPrefix('/auth');
$auth->get('/index', 'index');
$auth->get('/getUser', 'getUser');
$auth->get('/logout', 'logout');
if($user->isUser()) {
    $auth->get('/selectGuild/{guildId}', 'selectGuild');
    $auth->get('/getUserGuilds', 'getUserGuilds');
}
$app->mount($auth);

//if($user->isAdmin()) {
    $discordRequest = new MicroCollection();
    $discordRequest->setHandler(\Controllers\LocalApiController::class, true);
    $discordRequest->setLazy(true);
    $discordRequest->setPrefix('/localApi');
    $discordRequest->post('/setServerSettings', 'setServerSettings');
    $app->mount($discordRequest);
//}

/// temp only
$datac = new MicroCollection();
$datac->setHandler(\Controllers\DataCreateController::class, true);
$datac->setLazy(true);
$datac->setPrefix('/dataCreate');
$datac->get('/run', 'run');
$datac->get('/getFakeSource', 'getFakeSource');
$datac->post('/getSource', 'getSource');
$datac->post('/getSources', 'getSources');
$datac->get('/clear', 'clear');

$app->mount($datac);

$app->finish(function () use ($app) {
    $app->getDI()->get('instance')->toSession();
});

$app->error(function () use ($app) {
    $app->getDI()->get('instance')->toSession();
    if(!DEV) {
        $app
            ->response
            ->setStatusCode(500, 'Error')
            ->sendHeaders()
            ->send()
            ;
    }

});

$app->notFound(
    function () use ($app) {
        $app->getDI()->get('instance')->toSession();

        $message = 'Nothing to see here. Move along....';
        $app
            ->response
            ->setStatusCode(404, 'ZZ Not Found')
            ->sendHeaders()
            ->setContent($message)
            ->send()
        ;
    }
);

$app->handle($_SERVER["REQUEST_URI"]);
