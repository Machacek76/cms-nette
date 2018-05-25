<?php

namespace App\Model;

class AclAllowModel extends BaseModel
{
    public $tableName = 'acl_allow';


    /**
     * Check if role id is allow to resource id 
     *
     * @param integer $resourceID
     * @param integer $roleID
     * @return boolean 
     */
    public function getAllow (int $resourceID, int $roleID ){

        $res = $this->findOneBy(['id_acl_resource'=>$resourceID, 'id_acl_role'=>$roleID ]);
        
        if ($res){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * settings allow 
     *
     * @param integer $resourceID
     * @param integer $roleID
     * @param boolean $value
     * @return void
     */
    public function setAllow (int $roleID, int $resourceID, $value){
        $resLocal =  $this->findOneBy(['id_acl_resource'=>$resourceID, 'id_acl_role'=>$roleID]);

        if($value){
            if(!$resLocal){
                $this->insert(['id_acl_resource'=>$resourceID, 'id_acl_role'=>$roleID]);
            }
        }else{
            if($resLocal){
                $this->delete($resLocal->id);
            }
        }
    }




}