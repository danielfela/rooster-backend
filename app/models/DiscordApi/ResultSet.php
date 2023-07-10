<?php

namespace Model\DiscordApi;

use Phalcon\Mvc\Model\Exception;

class ResultSet implements \ArrayAccess, \Traversable, \Iterator, \JsonSerializable
{
    private array $data;
    private int $index = 0;

    /**
     * @throws Exception
     */
    public function __construct($data, $className)
    {
        if(!class_exists($className)) {
            throw new Exception('class '.$className.' not exists');
        }

        $this->data = array_map(function($rec) use ($className) {
            return new $className($rec);
        }, $data);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {

    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {

    }

    /**
     * @inheritDoc
     */
    public function current(): mixed
    {
        return $this->data[$this->index];
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->index++;
    }

    /**
     * @inheritDoc
     */
    public function key(): mixed
    {
        return $this->index;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return isset($this->data[$this->index]);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->data;
    }
}
