<?php

namespace Model\DiscordApi;

use Library\Api\Snowflake;

class GuildPart extends Result
{
    public Snowflake $id;
    public string $name;
    public ?string $icon;
    public bool $owner;
    public int $permissions;
    public array $features;
}
