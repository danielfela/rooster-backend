<?php

namespace Model\Database;

/**
 * @method static Languages findFirstByIso(string $_lang)
 *
 */
class Languages extends Model
{
    public ?int $id = null;
    public string $iso;
}
