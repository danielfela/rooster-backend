<?php

namespace Library\Traits;

use Phalcon\Support\HelperFactory;

trait magicMethodsHelper
{
    public function __call($name, $arguments)
    {
        $struct = explode('_', $this->helper->uncamelize($name));

        if ($struct[0] === 'get') {
            if (count($struct) === 3) {
                if (isset($this->{$struct[1]}) && is_object($this->{$struct[1]})) {
                    $something2 = $this->{$struct[1]};
                    if (isset($something2->{$struct[2]})) {
                        return $something2->{$struct[2]};
                    }
                }
            }

            if (count($struct) === 2) {
                if (isset($this->{$struct[1]})) {
                    return $this->{$struct[1]};
                }
            }
        }

        return null;
    }

}
