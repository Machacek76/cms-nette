<?php

namespace App\Security;

use Nette,
    Nette\Security\Permission;

class AuthorizatorFactory  {


    /** @var \App\Model\AclResourceModel */
    public $aclResourceModel;


    /** @var \App\Model\AclRoleModel */
    public $aclRoleModel;

    /** @var \App\Model\AclAllowModel */
    public $aclAllowModel;



    public function __construct(\App\Model\AclResourceModel $aclResourceModel, \App\Model\AclAllowModel $aclAllowModel, \App\Model\AclRoleModel $aclRoleModel)
    {
        $this->aclResourceModel = $aclResourceModel;
        $this->aclRoleModel = $aclRoleModel;
        $this->aclAllowModel = $aclAllowModel;
    }


    public function create()
    {
        $acl = new Permission;


        /** settings role DB */
        $res = $this->aclRoleModel->findAll();
        $rows = $res->fetchAll();
        $roles = [];
        foreach ($rows as $row){
            $roles[$row->id] = $row->role;
            $acl->addRole($row->role);
        }

        /** settings resource DB */
        $res = $this->aclResourceModel->findAll();
        $rows = $res->fetchAll();
        $resources = [];
        foreach ($rows as $row){
            $resources[$row->id] = $row->resource;
            $acl->addResource($row->resource);
        }
        

        //dump($roles, $resources);
        
        /** settings allow  DB */
        $res = $this->aclAllowModel->findAll();
        $rows = $res->fetchAll();
        foreach ($rows as $row){
        //   dump($row->id_acl_role . ' - ' . $row->id_acl_resource);
            $acl->allow($roles[ $row->id_acl_role], $resources[$row->id_acl_resource]);
        }
        
       // die;
        // admin
        $acl->allow('admin',  Permission::ALL, Permission::ALL);



        return $acl;
    }

}