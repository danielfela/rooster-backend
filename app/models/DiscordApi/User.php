<?php

namespace Model\DiscordApi;

use Library\Api\Snowflake;

class User extends Result
{
    /**
     * @var Snowflake snowflake (uint64), the user's id
     */
    public Snowflake $id;

    /**
     * @var string the user's username, not unique across the platform
     */
    public string $username;

    /**
     * @var string the user's 4-digit discord-tag
     */
    public string $discriminator;

    /**
     * @var string|null the user's avatar hash
     */
    public ?string $avatar = null;

    /**
     * @var bool|null whether the user belongs to an OAuth2 application
     */
    public ?bool $bot = null;

    /**
     * @var bool whether the user is an Official Discord System user (part of the urgent message system)
     */
    public bool $system;

    /**
     * @var bool whether the user has two factor enabled on their account
     */
    public bool $mfa_enabled;

    /**
     * @var string the user's banner hash
     */
    public ?string $banner = null;

    /**
     * @var ?int the user's banner color encoded as an int representation of hexadecimal color code
     */
    public ?int $accent_color = 0;

    /**
     * @var string the user's chosen language option
     */
    public ?string $locale = null;

    /**
     * @var bool  whether the email on this account has been verified email
     */
    public ?bool $verified = null;

    /**
     * @var string the user's email
     */
    public ?string $email = null;

    /**
     * @var int the flags on a user's account
     */
    public int $flags = 0;

    /**
     * @var int the type of Nitro subscription on a user's account
     */
    public ?int $premium_type = 0;

    /**
     * @var int the public flags on a user's account
     */
    public ?int $public_flags = 0;

}
