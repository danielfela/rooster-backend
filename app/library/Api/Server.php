<?php

namespace Library\Api;

class Server extends \Library\MVC\Injectable implements \JsonSerializable
{
    /** @var string[] $admins */
    public array $admins = [];
    /** @var string[] $adminRoles */
    public array $adminRoles = [];
    /** @var string[] $admins */
    public array $managers = [];
    /** @var string[] $managerRoles */
    public array $managerRoles = [];
    public bool $initialized = false;

    public function __construct($settings)
    {
        if (!$settings) {
            return null;
        }

        $this->initialized = true;

        if ($settings->managers) {
            $this->managers = $settings->managers;
        }

        if ($settings->admins) {
            $this->admins = $settings->admins;
        }

        if ($settings->managerRoles) {
            $this->managerRoles = $settings->managerRoles;
        }

        if ($settings->adminRoles) {
            $this->adminRoles = $settings->adminRoles;
        }
    }

    public function hasUserInAdmins($userId): bool
    {
        return in_array($userId, $this->admins);
    }

    public function hasRolesInAdmins(array $roles): bool
    {
        return !empty(array_intersect($roles, $this->adminRoles));
    }

    public function hasUserInManagers($userId): bool
    {
        return in_array($userId, $this->managers);
    }

    public function hasRolesInManagers(array $roles): bool
    {
        return !empty(array_intersect($roles, $this->managerRoles));
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): object
    {
        return (object)[
            'admins' => $this->admins,
            'adminRoles' => $this->adminRoles,
            'managers' => $this->managers,
            'managerRoles' => $this->managerRoles,
        ];
    }
}
