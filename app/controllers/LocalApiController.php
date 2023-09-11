<?php

namespace Controllers;

use Library\Http\Response;
use Model\Database\Server;

class LocalApiController extends \Library\MVC\Controller
{
    /**
     * @throws \ReflectionException
     */
    public function setServerSettings(): \Phalcon\Http\ResponseInterface
    {
        echo '+++++++++++++++++++++++++++++++++';
        $server = Server::findFirstById($this->instance->getGuildId());

        if (!$server) {
            $server = new Server();
            $server->setSettings($this->request->getJsonRawBody());
            $state = $server->create();
        } else {
            $server->setSettings($this->request->getJsonRawBody());
            $state = $server->save();
        }

        if (!$state) {
            return $this
                ->response
                ->setContent(join(',', $server->getMessages()))
                ->setStatusCode(503, 'Internal error');
        }

        return $this->response->setJsonContent(Response::getBaseResponse());
    }
}
