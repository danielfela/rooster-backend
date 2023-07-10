<?php

namespace Model\Database;

use library\Support\HelperFactory;
use Phalcon\Di\InjectionAwareInterface;

/**
 * @implements \Countable
 */
class Model extends \Phalcon\Mvc\Model implements InjectionAwareInterface
{
    public ?HelperFactory $helper = null;

    public function initialize(){
        $this->helper = $this->getDi()->get('helper');
    }

    public function columnMap(): array
    {
        if(!$this->helper) {
            $this->helper = $this->getDi()->get('helper');
        }

        $columns = $this->getModelsMetaData()->getAttributes($this);
        $map = [];

        foreach ($columns as $column)
        {
            $map[$column] = lcfirst($this->helper->camelize($column));
        }

        return $map;
    }

    public function __debugInfo() {
        $ret = [];
        foreach($this as $prop => $val) {
            if(!isset($a[$prop]) && !property_exists($this, $prop)) {
                $ret[$prop] = $val;
            }
        }

        return $ret;
    }
}
