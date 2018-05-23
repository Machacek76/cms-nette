<?php

namespace App\Security;

use Nette,
    Nette\Security\Permission;

class AuthorizatorFactory  {


    const ROLE_ADMIN    = 'admin';
    const ROLE_EDITOR   = 'editor';
    const ROLE_USER     = 'user';
    const ROLE_GUEST    = 'guest';


    public function create()
    {
        $acl = new Permission;

        $acl->addRole(self::ROLE_GUEST);
        $acl->addRole(self::ROLE_USER, self::ROLE_GUEST);
        $acl->addRole(self::ROLE_EDITOR, self::ROLE_USER);
        $acl->addRole(self::ROLE_ADMIN, self::ROLE_EDITOR);


        $acl->addResource('Admin');
        $acl->addResource('Admin:Homepage:default', 'Admin');
        $acl->addResource('Admin:User:forgot', 'Admin');
        $acl->addResource('Admin:User:reset', 'Admin');
        $acl->addResource('Admin:User:setting', 'Admin');
        $acl->addResource('Admin:User:default', 'Admin');
        $acl->addResource('Admin:User:all', 'Admin');

        $acl->addResource('Admin:Sign:in', 'Admin');

        // quest


        // user
        $acl->allow(self::ROLE_GUEST, 'Admin:Sign:in');
        $acl->allow(self::ROLE_GUEST, 'Admin:User:forgot');
        $acl->allow(self::ROLE_GUEST, 'Admin:User:reset');

        // editor
        $acl->allow(self::ROLE_EDITOR, 'Admin:Homepage:default');


        // admin
        $acl->allow(self::ROLE_ADMIN,  Permission::ALL, Permission::ALL);

        return $acl;
    }

}