<?php

namespace Library\MVC;

use Library\Instance\Instance;
use Phalcon\Support\HelperFactory;

/**
 * @property \Library\Translation\Adapter $translate
 * @property \Library\Cache\Adapter $cache
 * @property Instance $instance
 * @property HelperFactory $helper
 */
class Injectable extends \Phalcon\Di\Injectable
{

}
