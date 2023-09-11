<?php

namespace Library\Traits;

/**
 * @deprecated @see
 *
 * @property \Library\Translation\Adapter $translate
 * @property \Library\Cache\Adapter $cache
 * @property \Library\Http\Request $request
 * @property \Library\Http\Response $response
 * @property \Library\Support\HelperFactory $helper
 */
trait Injectables
{
    public function getTranslate(): \Library\Translation\Adapter
    {
    }

    public function getCache(): \Library\Cache\Adapter
    {
    }
}
