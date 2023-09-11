<?php

namespace Controllers;

use Library\Api\Discord;
use Library\Api\DiscordException;
use Library\Http\Response;
use Phalcon\Mvc\Model\Exception;

class DiscordRequestController extends BaseController
{
    use DiscordControllersTrait;

    /**
     * @throws Exception
     * @throws DiscordException
     * @throws \Exception
     *
     * @deprecated get guild, get members, get roles is not working on discord side
     * @see https://stackoverflow.com/questions/43269517/discord-add-guild-member-401-error-despite-apparently-valid-access-token
     * if ever need this information again
     */
    public function getGuildData(): \Phalcon\Http\ResponseInterface
    {
        $guildId = $this->instance->getGuildId();

        if (!$this->instance->hasToken()) {
            return $this->response->setStatusCode(401, 'Wrong or Unknown code');
        }

        if ($this->instance->hasToken()) {

            try {
                /*$guildMembers = $this->cache->toggle($guildId.'_guild_members3', function() {
                    //return ['members'];
                    $membersRequest = new Discord();
                    $url = $membersRequest->getGuildApiUrl('members');
                    var_dump($url);
                    $r = $membersRequest->execute($url)->getResponse();
                    var_dump($r);
                    return [];
                }, 3600);*/

                $guildRoles = $this->cache->toggle($guildId . '_guild_roles3', function () {

                    $request = new Discord();

                    return $request->execute(Discord::apiURLBase)->getResponse();


                    //return $rolesRequest->execute($membersRequest->getGuildApiUrl('roles'))->getResponse();
                }, 3600);
            } catch (DiscordException) {
                return $this->response->setStatusCode(503, 'Discord connection fail');
            }

            $response          = Response::getBaseResponse();
            $response->content = (object)[
                'roles' => $guildRoles,
            ];
            die();
            return $this->response->setJsonContent($response);

        } else {
            return $this->response->setStatusCode(422, 'Wrong or Unknown code');
        }


    }
}
