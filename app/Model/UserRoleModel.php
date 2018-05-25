<?php

namespace App\Model;






class UserRoleModel extends BaseModel{


    public $tableName = 'user_role';



    public function getRoles ($userId) {
        $res = $this->findBy(['id_user'=>$userId]);
        $rows = $res->fetchAll();
        $ret = [];
        foreach ($rows as $row){
            $ret[] = $row->role;
        }
        return $ret;
    }

    
}

