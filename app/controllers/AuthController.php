<?php

namespace Controllers;

use Library\Api\Discord;
use Library\Api\DiscordException;
use Library\Http\Response;
use Model\Database\Server;
use Model\DiscordApi\GuildPart;
use Model\DiscordApi\ResultSet;
use Model\DiscordApi\User;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Model\Exception;

class AuthController extends BaseController
{
    use DiscordControllersTrait;

    /**
     * @throws \ReflectionException
     */
    public function index(): \Phalcon\Http\ResponseInterface
    {
        if ($this->instance->hasToken()) {
            $response             = Response::getBaseResponse();
            $response->statusText = 'Previously Authorized';
            return $this->response->setJsonContent($response);
        }

        if ($this->request->hasQuery('code')) {
            $request = new Discord();

            $post = [
                "grant_type" => "authorization_code",
                'code'       => $this->request->getQuery('code'),
            ];

            try {
                $request
                    ->setPost($post)
                    ->setHeaders(['content-type' => 'application/x-www-form-urlencoded'])
                    ->execute(Discord::tokenURL);
            } catch (DiscordException) {
                return $this->response->setStatusCode(503, 'Discord connection fail');
            }

            $tokenRespJson = $request->getResponse();

            if (isset($tokenRespJson->access_token) && !empty($tokenRespJson->access_token)) {
                $this->instance->setToken($tokenRespJson);
                $response             = Response::getBaseResponse();
                $response->statusText = 'Authorized';
                return $this->response->setJsonContent($response);
            } else {
                return $this->response->setStatusCode(401, 'Unauthorized');
            }

        } else {
            return $this->response->setStatusCode(422, 'Wrong or Unknown code');
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function getUser(): \Phalcon\Http\ResponseInterface
    {
        if ($this->instance->hasUser()) {
            $response          = Response::getBaseResponse();
            $response->content = $this->instance;

            return $this->response->setJsonContent($response);
        }
        if ($this->instance->hasToken()) {
            $request = new Discord();

            try {
                $this->instance->setUser($request->execute(Discord::apiURLBase)->getResponse());
            } catch (DiscordException) {
                return $this->response->setStatusCode(503, 'Discord connection fail');
            }

            $response          = Response::getBaseResponse();
            $response->content = $this->instance;

            return $this->response->setJsonContent($response);

        } else {
            return $this->response->setStatusCode(422, 'Wrong or Unknown code');
        }
    }

    public function destroyUser()
    {
        $this->instance->clear();
        $this->cookies->delete(SESSION_NAME);
        $this->session->destroy();
    }

    /**
     * @throws Exception
     */
    public function getUserGuilds(): \Phalcon\Http\ResponseInterface
    {
        if ($this->instance->hasUser()) {

            try {
                $guilds = $this->getGuildsList();
            } catch (DiscordException) {
                return $this->response->setContent('Discord connection fail')->setStatusCode(503);
            }

            $response          = Response::getBaseResponse();
            $response->content = $guilds;

            return $this->response->setJsonContent($response);
        } else {

            $this->destroyUser();
            return $this->response->setContent('Authentication required - request user first')->setStatusCode(511);
        }
    }

    /**
     * @throws \ReflectionException
     * @throws Exception
     */
    public function selectGuild($guildId): ResponseInterface
    {

        if ($this->instance->hasUser()) {

            try {
                $guilds = $this->getGuildsList();
            } catch (DiscordException) {
                return $this->response->setContent('Discord connection fail')->setStatusCode(503);
            }

            $selectedGuild = null;
            foreach ($guilds as $guild) {
                if ((string)$guild->id === $guildId) {
                    $selectedGuild = $guild;
                    break;
                }
            }

            if (!$selectedGuild) {
                return $this->response->setContent('bad guild id provided')->setStatusCode(400);
            }

            $this->instance->setGuild($selectedGuild);
            $this->instance->saveGuild();

            $requestMember = new Discord();

            try {
                $this->instance->setMember(
                    $requestMember->execute(sprintf(Discord::apiGuildMember, $guildId))->getResponse()
                );
                $this->instance->saveMembership();
            } catch (DiscordException) {
                return $this->response->setStatusCode(503)->setContent('Discord connection fail');
            }

            //$this->cache->delete($userId.'_guild_list');

            $response = Response::getBaseResponse();
            return $this->response->setJsonContent($response);
        } else {
            $this->destroyUser();
            return $this->response->setContent('Authentication required - request user first')->setStatusCode(511);
        }
    }

    function logout()
    {
        if ($this->instance->hasToken()) {
            $request = new Discord();

            $request
                ->setHeaders(['content-type' => 'application/x-www-form-urlencoded'])
                ->setPost(['token' => $this->session->get('token')->access_token])
                ->execute(Discord::revokeURL);
        }

        $this->destroyUser();

    }
}
