<?php

namespace App\Model;






class UserRoleModel extends BaseModel{


    public $tableName = 'user_role';



    public function getRoles ($userId, $fullInfo = NULL) {
        
        $sql = 'SELECT user_role.* , acl_role.role FROM user_role
                INNER JOIN acl_role ON  user_role.id_acl_role = acl_role.id  where id_user = ? ;';
    
        $res = $this->database->query($sql, $userId);
        $rows = $res->fetchAll();
        $ret = [];

        foreach ($rows as $row){
            if($fullInfo){
                $ret[] = $row;
            }else{
                $ret[] = $row->role;
            }
        }

        return $ret;
    }



    
}

