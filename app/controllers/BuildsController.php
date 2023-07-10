<?php

namespace Controllers;

use Attribute;
use Library\Http\Response;
use Model\Database\Builds;
use Model\Database\Users;

#[Attribute]
class ApiGet {}

class BuildsController extends LocalApiController
{
/**
 * @route save/
 */
  public function save(): \Phalcon\Http\ResponseInterface
  {

      $b = new Builds();
      $b->content = $this->request->getRawBody();

      if(!($player = $this->request->getFromJson('player'))) {
          $player = $this->instance->getUserId();
      }

      $b->player = $player;
      $b->build = $this->request->getFromJson('build');
      if($b->save())
      {
          return $this->response
              ->setContent('Created')
              ->setStatusCode(201, 'Created');
      }
      else
      {
          return $this->response
              ->setContent(implode('<br />', $b->getMessages()))
              ->setStatusCode(400, 'Bad Request');
      }
  }

    /**
     * @route get/
     */
  public function get(): \Phalcon\Http\ResponseInterface
  {
      //get single builds
      if($this->request->hasPost('build')) {
          $player = $this->request->hasPost('player')
              ? $this->request->getPost('player')
              : $this->instance->getUserId();

          $res = Builds::findFirstByPlayerAndBuild(
              $player,
              $this->request->hasPost('build'),
          );

          if($res)
          {
              return $this->response->setJsonContent($res->content);
          }
          else
          {
              return $this->response->setStatusCode(404, 'Record not found');
          }
      }

      //get multiple builds
      if($this->request->hasPost('player')) { //selected user builds
          $res = Builds::findByPlayer(
              $this->request->getPost('player'),
          );
      }
      else if (!$this->instance->isManager()) { //only current user builds
          $res = Builds::findByPlayer(
              $this->instance->getUserId(),
          );
      }
      else //all builds
      {
          $res = Builds::find();
      }

      if($res->count())
      {
          $ret = [];
          $res->rewind();
          while ($res->valid()) {
              $ret[] = $res->current()->content;
              $res->next();
          }

          $response          = Response::getBaseResponse();
          $response->content = (object)['builds' => $ret, 'players' => Users::find()];
          return $this->response->setJsonContent($response);
      }
      else
      {
          $response = Response::getBaseResponse();
          $response->status = 404;
          $response->statusText = 'No records found';
          return $this->response
              ->setJsonContent($response)
              ->setStatusCode(404, 'No records found');
      }
  }
}
