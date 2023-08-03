<?php

namespace Library\Http;

use Phalcon\Di\DiInterface;
/**
 * @method setDI(DiInterface $container): void
 * @method getDI(): DiInterface
 * @implements \Countable
 */
class Request extends \Phalcon\Http\Request
{
    protected $bodyJson;

    public function __construct(){
        $this->bodyJson = $this->getJsonRawBody();
    }
    function getFromJson(string $_path) {
        $path = explode('.', $_path);
        $node = $this->bodyJson;
        while($node !== null && !empty($path) && $path[0] !== '') {
            $node = $this->getJsonNode($node, array_shift($path));
            if(is_scalar($node) || $node === null) {
                break;
            }
        }

        return $node;
    }

    private function getJsonNode(object | null $_body, string $_prop) {
        $body = $_body ?? $this->bodyJson;

        if(isset($body->$_prop)) {
            return $body->$_prop;
        }

        return null;
    }

    public function getRawJsonBody() {
        return $this->bodyJson;
    }
}
