<?php

namespace Library\Instance;

use Library\Api\Discord;
use Library\Traits\magicMethodsHelper;
use Model\Database\Server;
use Model\Database\Server as ServerModel;
use \Library\Api\Server as ServerApi;
use Model\Database\Users;
use Model\DiscordApi\GuildPart;
use Model\DiscordApi\Member;
use Model\DiscordApi\Token;
use Model\DiscordApi\User;

/**
 * @method getUserId(): string
 * @method getGuildId(): string
 * @method getMembershipNick(): string
 * @method getUserUsername(): string
 */
class Instance extends \Library\MVC\Injectable implements \JsonSerializable
{
    use magicMethodsHelper;

    const _PERMISSION_NONE = 0;
    const _PERMISSION_USER = 1;
    const _PERMISSION_MANAGER = 2;
    const _PERMISSION_ADMIN = 3;
    private ?User $user = null;
    private ?GuildPart $guild = null;
    private ?\Library\Api\Server $server = null;
    private ?Member $membership = null;
    private int $permissions = self::_PERMISSION_NONE;
    public ?Token $token = null;

    /**
     * @throws \ReflectionException
     */
    public function __construct()
    {
        if ($this->session->has('token')) {
            $this->setToken($this->session->get('token'));

            if ($this->session->has('user')) {
                $this->setUser($this->session->get('user'));

                if ($this->session->has('guild')) {
                    $this->setGuild($this->session->get('guild'));

                    if ($server = ServerModel::findFirstById($this->guild->id)) {
                        $this->setServer($server->getSettings());
                    }

                    if ($this->session->has('guild-member')) {
                        $this->setMember($this->session->get('guild-member'));
                    }
                }
            }
        }

        $this->setPermissions();
    }

    public function updatePermissions(int $permission)
    {
        if ($permission > $this->permissions) {
            $this->permissions = $permission;
        }
    }

    public function setPermissions()
    {
        if ($this->guild && $this->guild->owner) {
            $this->updatePermissions(self::_PERMISSION_ADMIN);
            return;
        }

        if ($this->user && $this->server) {
            if ($this->server->hasUserInAdmins($this->user->id)) {
                $this->updatePermissions(self::_PERMISSION_ADMIN);
            }

            if ($this->server->hasUserInManagers($this->user->id)) {
                $this->updatePermissions(self::_PERMISSION_MANAGER);
            }
        }

        if ($this->user && $this->server && $this->membership) {
            if ($this->server->hasRolesInAdmins($this->membership->roles)) {
                $this->updatePermissions(self::_PERMISSION_ADMIN);
            }

            if ($this->server->hasUserInManagers($this->membership->roles)) {
                $this->updatePermissions(self::_PERMISSION_MANAGER);
            }
        }

        if ($this->user) {
            $this->updatePermissions(self::_PERMISSION_USER);
        }
    }

    public function getPermissions(): int
    {
        return $this->permissions;
    }

    public function isAdmin(): bool
    {
        return $this->permissions === self::_PERMISSION_ADMIN;
    }

    public function isManager(): bool
    {
        return $this->permissions >= self::_PERMISSION_MANAGER;
    }

    public function isUser(): bool
    {
        return $this->permissions >= self::_PERMISSION_USER;
    }

    /**
     * @throws \ReflectionException
     */
    public function setToken($token)
    {
        $this->token = $token instanceof Token ? $token : new Token($token);
    }

    /**
     * @throws \ReflectionException
     */
    public function setUser($user)
    {
        $this->user = $user instanceof User ? $user : new User($user);
    }

    /**
     * @throws \ReflectionException
     */
    public function setGuild($guild)
    {
        $this->guild = $guild instanceof GuildPart ? $guild : new GuildPart($guild);
    }

    /**
     * @throws \ReflectionException
     */
    public function setMember($member)
    {
        $this->membership = $member instanceof Member ? $member : new Member($member);
    }

    public function setServer($server)
    {
        if ($server instanceof ServerApi) {
            $this->server = $server;
            return;
        }

        if ($server instanceof ServerModel) {
            $this->server = new ServerApi($server->getSettings());
            return;
        }

        $this->server = new ServerApi($server);
    }

    public function getToken(): ?Token
    {
        return $this->token;
    }

    /**
     * @throws \ReflectionException
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @throws \ReflectionException
     */
    public function getGuild(): ?GuildPart
    {
        return $this->guild;
    }

    /**
     * @throws \ReflectionException
     */
    public function getMember(): ?Member
    {
        return $this->membership;
    }

    /**
     * @throws \ReflectionException
     */
    public function getServer(): ?ServerApi
    {
        return $this->server;
    }

    public function hasToken(): bool
    {
        return $this->token instanceof Token;
    }

    public function hasUser(): bool
    {
        return $this->user instanceof User;
    }

    public function hasGuild(): bool
    {
        return $this->guild instanceof GuildPart;
    }

    /**
     * @throws \ReflectionException
     */
    public function hasMember(): bool
    {
        return $this->membership instanceof Member;
    }

    public function hasServer(): bool
    {
        return $this->server instanceof ServerApi;
    }

    public function __set(string $propertyName, $value): void
    {
        if (method_exists(self::class, $method = 'set' . ucfirst($propertyName))) {
            $this->$method($value);
        }
    }

    public function __get(string $propertyName): mixed
    {
        if (method_exists(self::class, $method = 'get' . ucfirst($propertyName))) {
            return $this->$method();
        }

        return parent::__get($propertyName);
    }

    public function __isset(string $name): bool
    {
        if (method_exists(self::class, $method = 'has' . ucfirst($name))) {
            return $this->$method();
        }

        return parent::__isset($name);
    }

    public function has($name): bool
    {
        return isset($name);
    }

    /**
     * Store base data about player to database, to be used for base identities' ex. when restoring username for rooster
     *
     * @throws \ReflectionException
     */
    public function storeUserRef()
    {
        if ($this->hasUser()) {
            if (!$this->hasMember() || empty($nick = $this->getMembershipNick())) {
                $nick = $this->getUserUsername();
            }

            $storedUser = Users::findFirstById($this->getUserId());
            if (!$storedUser) {
                $storedUser = new Users();
                $storedUser->id = $this->getUserId();
            }
            if ($nick) {
                $storedUser->name = $nick;
            }

            $storedUser->save();
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function saveUser()
    {
        $this->session->set('user', $this->user);
        $this->storeUserRef();
    }

    public function saveGuild()
    {
        $this->session->set('guild', $this->guild);
    }

    public function saveMembership()
    {
        $this->session->set('guild-member', $this->membership);
    }

    public function saveToken()
    {
        $this->session->set('token', $this->token);
    }

    public function toSession()
    {
        if ($this->token && !$this->session->has('token')) {
            $this->saveToken();
        }
        $this->storeUserRef();
        if ($this->user && !$this->session->has('user')) {
            $this->saveUser();
        }

        if ($this->guild && !$this->session->has('guild')) {
            $this->saveGuild();
        }

        if ($this->membership && !$this->session->has('guild-member')) {
            $this->saveMembership();
        }
    }

    public function clear($fromSession = true)
    {
        $this->token = null;
        $this->user = null;
        $this->guild = null;
        $this->permissions = self::_PERMISSION_NONE;
        $this->membership = null;

        if ($fromSession) {
            $this->session->remove('token');
            $this->session->remove('user');
            $this->session->remove('guild');
            $this->session->remove('guild-member');
        }
    }

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function jsonSerialize(): object
    {
        $ret = (object)[];
        $ret->user = $this->user;
        if ($this->hasGuild()) {
            $ret->guild = $this->guild;
        }
        if ($this->hasMember()) {
            $ret->member = $this->membership;
        }
        if ($this->hasServer()) {
            $ret->server = $this->getServer();
        }

        return $ret;
    }
}
