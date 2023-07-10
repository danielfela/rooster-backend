<?php

namespace Model\Database;

class Log extends Model
{
    public string $type;
    public string $ref;
    public string $request;
    public string $response;
    public string $source;
    public int $time;
    public string $headers;
    public string $state = 'ok';

    public function initialize()
    {
        $this->skipAttributes(
            [
                'time',
            ]
        );
    }
}
