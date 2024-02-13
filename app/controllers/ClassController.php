<?php

namespace controllers;

use Library\Http\Response;
use Model\Database\ClassType;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Validation;

class ClassController extends BaseController
{
    public function get(): \Phalcon\Http\ResponseInterface
    {
        $response          = Response::getBaseResponse();
        $response->content = ClassType::find();

        return $this->response->setJsonContent($response);
    }

    public function create(): \Phalcon\Http\ResponseInterface
    {
        if(!$this->request->isPost()) {
            return $this->response->setStatusCode(400);
        }

        if(!parent::checkPost($this->getRules())) {
            return $this->response->setStatusCode(400);
        }

        $response          = Response::getBaseResponse();

        return $this->response->setJsonContent($response);
    }

    private function getRules(): array
    {
        return [
            'name' => [
                'PresenceOf' => true,
                'StringLength' => [5, 20],
            ],
            'label' => [
                'PresenceOf' => true,
                'StringLength' => [1, 10],
            ],
            'description' => [
                'StringLength' => [10, 200],
            ],
            'mainClass' => [
                'Digit' => true,
            ],
            'color' => [
                'PresenceOf' => true,
                'StringLength' => [3, 3],
                'Numericality' => true,
            ]
        ];
    }
}
