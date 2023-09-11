<?php
const SESSION_NAME = 'super_secret_session_name';
date_default_timezone_set('UTC');
//define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: 'development');
const APPLICATION_ENV = 'development';
const DEV             = APPLICATION_ENV === 'development';
if (APPLICATION_ENV === 'development') {
    ini_set('display_errors', 'On');
    ini_set('xdebug.var_display_max_depth', '5');

    error_reporting(E_ALL);
    $debug = new \Phalcon\Support\Debug();
    $debug->listen();
}

const OAUTH2_CLIENT_ID     = '1090667972403671080';
const OAUTH2_CLIENT_SECRET = '5RKPOgq51yjNui6Mlc_qcE9PfxBYESEr';
const OAUTH2_REDIRECT      = 'http://localhost:3000/authorize';

define('APP_PATH', realpath('..') . '/app');

const IS_DISCORD = true;
/** @var \Phalcon\Di\FactoryDefault $di */
try {

    /*
     * Read the configuration
     */
    $config = include APP_PATH . '/config/config.php';

    /**
     * Include Autoloader.
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Include Services.
     */
    include APP_PATH . '/config/services.php';

    /**
     * Include ACL.
     */
    // include APP_PATH . 'app/config/acl.php';

    include APP_PATH . '/config/app.php';


    //$app = new \Phalcon\Mvc\Application($di);

    /*
     * Handle the request
     */
    /**
     * @var $app
     */


    //$app->handle($_SERVER["REQUEST_URI"]);

} catch (\Exception $e) {

    if (APPLICATION_ENV === 'development') {
        var_dump($e);
        print_r($e->getMessage() . '<br>');
        print_r('<pre>' . $e->getTraceAsString() . '</pre>');
    }

    return (new \Library\Http\Response())
        ->setStatusCode(500, 'Error')
        ->sendHeaders()
        ->send();

}
