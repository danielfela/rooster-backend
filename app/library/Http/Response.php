<?php

namespace Library\Http;

use http\Exception\UnexpectedValueException;
use Phalcon\Http\ResponseInterface;

class Response extends \Phalcon\Http\Response
{
    private bool $flushOnEnd = false;

    public function setFlush($state): static
    {
        $this->flushOnEnd = $state;
        return $this;
    }

    static function getBaseResponse(): BaseResponse
    {
        return new BaseResponse();
    }

    public function setStatusCode(int $code, string $message = null): ResponseInterface
    {
        if ($this->flushOnEnd) {
            $this->setContent('');
            ob_clean();
        }

        return parent::setStatusCode($code, $message);
    }

    public function setJsonContent(
        mixed $content,
        int $jsonOptions = 0,
        int $depth = 512
    ): \Phalcon\Http\ResponseInterface {
        $this->setContent('');
        ob_clean();
        if (!($content instanceof BaseResponse)) {
            $res = self::getBaseResponse();
            $res->content = $content;
        } else {
            $res = $content;
        }

        /* if(!isset($content->content)) {
             $content->content = $content;
         }

         if(!isset($content->contentType)) {
             $content->contentType = 'application/json';
         }

         if(!isset($content->bodyText)) {
             $content->bodyText = '';
         }

         if(!isset($content->ok)) {
             $content->ok = true;
         }

         if(!isset($content->statusText)) {
             $content->statusText = '';
         }

         if(!isset($content->status)) {
             $content->status = 200;
         }*/

        return parent::setJsonContent($res, $jsonOptions = 0, $depth = 512);
    }
}
