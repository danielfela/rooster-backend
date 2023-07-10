<?php

namespace Model\DiscordApi;

class Member extends Result
{
    /*
     * @var User|null the user this guild member represents

    public ?User $user = null;*/

    /**
     * @var string this user's guild nickname
     */
    public ?string $nick = null;

    /**
     * @var string this user's guild avatar hash
     */
    public ?string $avatar = null;

    /**
     * @var array array of role object ids
     */
    public array $roles = [];

    /**
     * @var array ISO8601 timestamp  when the user joined the guild
     */
    public string $joined_at;

    /**
     * @var string ISO8601 timestamp  when the user started boosting the guild
     */
    public ?string $premium_since = null;

    /**
     * @var bool whether the user is deafened in voice channels
     */
    public bool $deaf;

    /**
     * @var bool hether the user is muted in voice channels
     */
    public bool $mute;

    /**
     * @var int guild member flags represented as a bit set,
     */
    public ?int $flags = 0;

    /**
     * @var bool whether the user has not yet passed the guild's Membership Screening requirements
     */
    public ?bool $pending = false;

    /**
     * @var string total permissions of the member in the channel,
     *             including overwrites, returned when in the interaction object
     */
    public ?string $permissions = null;

    /**
     * @var string ISO8601 timestamp  when the user's timeout will expire
     *            and the user will be able to communicate in the guild again,
     *            null or a time in the past if the user is not timed out
     */
    public ?string $communication_disabled_until = null;
}
