<?php

namespace Library\App;

class Loader extends \Phalcon\Loader
{
    public function autoload($className): bool
    {
        var_dump($className);
        parent::autoLoad(strtolower($className));
    }
}
