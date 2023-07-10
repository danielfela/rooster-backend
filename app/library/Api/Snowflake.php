<?php

namespace Library\Api;

class Snowflake implements \Stringable
{
    private string $snowflake;
    public function __construct(string $snowflake)
    {
        $this->snowflake = $snowflake;
    }

    public function __get(string $name)
    {
        if($name === 'snowflake') {
            return $this->snowflake;
        }

        return null;
    }


    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->snowflake;
    }
}
