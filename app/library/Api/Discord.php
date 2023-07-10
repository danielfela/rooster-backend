<?php

namespace Library\Api;


use Model\Database\Log;

class Discord extends \Library\MVC\Injectable
{
    const authorizeURL = 'https://discord.com/api/oauth2/authorize';
    const tokenURL = 'https://discord.com/api/oauth2/token';
    const apiURLBase = 'https://discord.com/api/users/@me';
    const revokeURL = 'https://discord.com/api/oauth2/token/revoke';
    const apiGuildMember = 'https://discord.com/api/users/@me/guilds/%s/member'; //? = guild id
    const apiGuild = 'https://discord.com/api/guilds/%s/%s'; //? = guild id
    const apiGuildsList = 'https://discord.com/api/users/@me/guilds';


    private array $post = [];
    private array $headers = [];
    private false|\CurlHandle $handler;
    private mixed $response;
    private string|bool|null $raw;
    private string|null $error = null;

    public function __construct()
    {
        $this->initHandler();
        $this->headers['accept'] = 'application/json';
    }

    public function initHandler(): Discord
    {
        $this->handler = curl_init();
        return $this;
    }

    public function setPost(array $post): Discord
    {
        $this->post = $post;
        return $this;
    }

    public function setHeaders(array $headers): Discord
    {
        $this->headers = $headers;
        return $this;
    }

    public function getRawResponse(): bool|string
    {
        return $this->raw;
    }
    public function getResponse(): mixed
    {
        return $this->response;
    }

    public function addHeaders(array $headers): Discord
    {
        $this->headers = [...$this->headers, ...$headers];
        return $this;
    }

    public function isTokenRequest($url): bool
    {
        return $url === self::tokenURL || $url === self::revokeURL;
    }

    public function getGuildApiUrl($method): string
    {
        return sprintf(self::apiGuild, $this->instance->getUserId(), $method);
    }

    /**
     * @throws DiscordException
     * @throws \ReflectionException
     */
    public function execute($url): Discord
    {
        if(!$this->session->has('discord-request-count')) {
            $this->session->set('discord-request-count', 1);
        }
        else {
            $this->session->set('discord-request-count', (int)$this->session->get('discord-request-count') + 1);
        }

      // var_dump(curl_getinfo($this->handler));
        if(curl_getinfo($this->handler, CURLINFO_HTTP_CODE) !== 0){
            //second execute attempt, need to rebuild curl handler
            //$this->handler = curl_init();
            $this->initHandler();
        }

        //var_dump('execute', $url);
        //there should not be more than 3 request on session - request, refresh if unauthorized and request again after authorization
        if((int)$this->session->get('discord-request-count') > 3) {
            die('too many discord request for session');
        }
      //  var_dump('counts', $this->session->get('discord-request-count'));
        curl_setopt($this->handler, CURLOPT_URL, $url);
        curl_setopt($this->handler, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);

        if (!empty($this->post)) {
            if($this->isTokenRequest($url)){
                $postBase = [
                    'client_id'     => OAUTH2_CLIENT_ID,
                    'client_secret' => OAUTH2_CLIENT_SECRET,
                    'redirect_uri'  => OAUTH2_REDIRECT,
                ];
            }

            $this->setPost([...$postBase, ...$this->post]);
            curl_setopt($this->handler, CURLOPT_POST, true);
            curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($this->post));
        }

        //every request to Discord Api needs token - except request for token itself
        if(!$this->isTokenRequest($url)) {

            $this->addHeaders(['Authorization' => 'Bearer ' . $this->instance->getToken()->accessToken]);
        }

        $headers = [];
        foreach($this->headers as $name => $value) {
            $headers[] = $name.': '.$value;
        }

        curl_setopt($this->handler, CURLOPT_HTTPHEADER, $headers);

        $this->raw = curl_exec($this->handler);

        $this->response = json_decode($this->raw);

//var_dump([$this->response , $this->raw, curl_getinfo($this->handler), 'token' => $this->session->get('token'), 'headers' => $this->headers]);

        $log = new Log();

        $log->type = $this->isTokenRequest($url) ? 'auth' :'request';
        $log->ref = 'code';
        $log->source = 'discord';
        $log->headers = join(',', $this->headers);
        $log->request = json_encode($this->post);
        $log->response = $this->raw;

        if(curl_getinfo($this->handler, CURLINFO_HTTP_CODE) === 401 && !$this->isTokenRequest($url)) {
            $refreshRequest = new Discord();

            $rpost = [
                "grant_type"    => "refresh_token",
                'refresh_token' => $this->instance->getToken()->refreshToken,
            ];

            $resp = $refreshRequest
                ->setPost($rpost)
                ->setHeaders(['content-type' => 'application/x-www-form-urlencoded'])
                ->execute(Discord::tokenURL)
                ->getResponse();
          //  var_dump('refresh', $resp);
            if(isset($resp->access_token) && !empty($resp->access_token)) {

                $this->instance->setToken($resp);
                $this->instance->saveToken();
                $log->save(); //save current log
                return $this->execute($url); //execute again
            }
            else {
                $this->error = 'discord server fail 2';
            }
        }

        if (curl_errno($this->handler)) {
            $this->error = 'curl fail';
        }

        if (curl_getinfo($this->handler, CURLINFO_HTTP_CODE) !== 200) {
            $this->error = 'discord server fail 3';
        }
        if($this->error) {
            $log->state = 'err';
        }

        $log->save();

        if($this->error) {
            throw new DiscordException($this->error);
        }

        return $this;
    }
}
