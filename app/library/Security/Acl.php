<?php

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;

/**
 * @deprecated
 */
class Acl extends Phalcon\Acl\Adapter\Memory
{

    public function __construct()
    {
        parent::__construct();

        $this->addRole('admin');
        $this->addRole('manager');
        $this->addRole('user');


        /**
         * Add the Components
         */

        $this->addComponent(
            'auth',
            [
                'dashboard',
                'users',
                'view',
            ]
        );

        $this->addComponent(
            'reports',
            [
                'list',
                'add',
                'view',
            ]
        );

        $this->addComponent(
            'session',
            [
                'login',
                'logout',
            ]
        );

        /**
         * Now tie them all together
         */
        //$acl->allow('manager', 'admin', 'users');
        //$acl->allow('manager', 'reports', ['list', 'add']);
        // $acl->allow('*', 'session', '*');
        $this->allow('*', 'auth', 'view');
        // $acl->deny('guest', '*', 'view');
    }


}
