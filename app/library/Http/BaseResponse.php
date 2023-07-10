<?php

namespace Library\Http;

class BaseResponse
{
    public bool $ok = true;
    public int $status = 200;
    public string $statusText = '';
    public string $bodyText = '';
    public string $contentType = '';
    public mixed $content;

    public function __construct()
    {
        $this->content = new class{};
    }
}
