<?php

namespace Model\Database;

/**
 * @method static findFirstByKey($key): self
 */
class NwtextsProps extends Model
{
    public ?int $id = null;
    public string $module;
    public string $key;
}
