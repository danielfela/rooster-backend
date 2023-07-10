<?php

$config = new \Phalcon\Config\Config([
    'application' => [
        'title' => 'API REST',
        'description' => 'API REST',
        'controllersDir' => APP_PATH . '/controllers/',
        'libraryDir' => APP_PATH . '/library/',
        'modelsDir' => APP_PATH . '/models/',
        'viewsDir' => APP_PATH . '/views/',
        'baseUri' => '/',
    ],
    'database' => [

        'host' => 'localhost',
        'password' => 'C8GyRyCnVdc0SXeHuCD0',
        'username' => 'danielfela_gondor',
        'dbname' => 'danielfela_gondor',
        'adapter' => 'mysql',
        'port'     => 3306,
    ],
]);
//pass e5]!u7B7N7YBdm
return $config;
