<?php

namespace Controllers;

use Library\Api\Discord;
use Library\Api\DiscordException;
use Library\Instance\Instance;
use Model\DiscordApi\GuildPart;
use Model\DiscordApi\ResultSet;
use Phalcon\Cache\Cache;
use Phalcon\Mvc\Model\Exception;

/**
 * @property Instance instance
 * @property Cache cache
 */
trait DiscordControllersTrait
{
    /**
     * @return GuildPart[]
     * @throws DiscordException
     *
     * @throws Exception
     */
    public function getGuildsList(): ResultSet|array
    {
        $userId = $this->instance->getUserId();

        $guilds = $this->cache->get($userId . '_guild_list');

        if (!$guilds) {
            $request = new Discord();

            $guilds = new ResultSet($request->execute(Discord::apiGuildsList)->getResponse(), GuildPart::class);

            $this->cache->set($userId . '_guild_list', $guilds, 600);
        }

        return $guilds;
    }
}
