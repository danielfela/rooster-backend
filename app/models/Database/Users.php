<?php

namespace Model\Database;

/**
 * @method static Users findFirstById($id): Users
 */
class Users extends Model
{
    public string $id;
    public string $name;
}
