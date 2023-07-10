<?php
namespace PHPSTORM_META {

    use Controllers\AuthController;
    use Library\Cache\Adapter;
    use Library\MVC\Controller;

    override(\Phalcon\Di\DiInterface::get(0), map([
        "cache" => Adapter::class,
        "instance" => \Instance::class,
    ]));
    override(\Phalcon\Di\DiInterface::getShared(0), map([
        "cache" => Adapter::class,
        "instance" => \Instance::class,
    ]));
    override(\Phalcon\Di\DiInterface::get("cache"), map([
        "cache" => Adapter::class,
    ]));
    override(\Phalcon\Di\DiInterface::getShared("cache"), map([
        "cache" => Adapter::class,
    ]));
    override(\Phalcon\Di\DiInterface::get("cache"),Adapter::class);
    override(\Phalcon\Di\DiInterface::getShared("cache"),Adapter::class);
    override(\Phalcon\Di::getShared(0), map([
        "cache" => Adapter::class,
    ]));
    override(\Phalcon\Di::get(0), map([
        "cache" => Adapter::class,
    ]));
    override(\Phalcon\Di\DiInterface::get("instance"),\Instance::class);
    override(\Phalcon\Di\DiInterface::getInstance(),\Instance::class);
    override(\Phalcon\Di\DiInterface::getShared("instance"),\Instance::class);

    override((new AuthController)->instance,\Instance::class);
    override(AuthController::class, map([
        "instance" => \Instance::class,
    ]));
    expectedReturnValues(AuthController::instance,\Instance::class);
    expectedArguments(
        AuthController::addArgument(),
        0,
        \Instance::_PERMISSION_USER,
        \Instance::_PERMISSION_ADMIN,
        \Instance::_PERMISSION_MANAGER,
    );

    override(AuthController::__get(0),
        map([
            'instance' => \Instance::class,
        ]));

    override(AuthController::__get('instance'), \Instance::class);
// This file is not a CODE, it makes no sense and won't run or validate
// Its AST serves IDE as DATA source to make advanced type inference decisions.
//
// https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata



}
