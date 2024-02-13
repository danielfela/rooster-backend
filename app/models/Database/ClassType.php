<?php

namespace Model\Database;

use Model\Database\Model;

class ClassType extends Model
{
    public int $id;
    public string $name;
    public string $label;
    public ?string $description;
    public ?int $mainClass;
    public string $color;
}
