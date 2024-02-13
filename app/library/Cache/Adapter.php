<?php

namespace Library\Cache;

use Phalcon\Cache\Adapter\Apcu;
use Phalcon\Storage\SerializerFactory;

class Adapter extends Apcu
{
    public function __construct()
    {
        $serializerFactory = new SerializerFactory();

        $options = [
            'defaultSerializer' => 'php',
            'lifetime' => 7200,
        ];

        parent::__construct($serializerFactory, $options);
    }

    /**
     * @throws \Exception
     */
    public function toggle($_key, $_callable, $_ttl = null)
    {
        /*if($this->has($_key)){
            return $this->get($_key);
        }*/

        $ret = $_callable();

        $this->set($_key, $ret, $_ttl);

        return $ret;
    }
}
