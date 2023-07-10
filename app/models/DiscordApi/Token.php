<?php

namespace Model\DiscordApi;

/**
 * @property-read string accessToken
 * @property-read string refreshToken
 */
class Token extends Result
{
    public string $access_token;
    public string $token_type;
    public int $expires_in;
    public string $refresh_token;
    public string $scope;
}
