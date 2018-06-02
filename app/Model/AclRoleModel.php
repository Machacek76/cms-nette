<?php

namespace App\Model;

class AclRoleModel extends BaseModel{
    
    public $tableName = 'acl_role';


    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return array
     */
    public function getRole ($id) {
        if($id === 'all'){
            return $this->findAll()->fetchAll();
        }else{
            return $this->findBy(['id'=>$id])->fetch();
        }
    }


    

}