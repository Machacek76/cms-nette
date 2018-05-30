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
        return $this->findOneBy(['id_acl_resource'=>$resourceID, 'id_acl_role'=>$roleID ]);
    }

    /**
     * settings allow 
     *
     * @param integer $resourceID
     * @param integer $roleID
     * @param mixed $value
     * @param string $target resourceID / privilegeID
     * @return void
     */
    public function setAllow (int $roleID, int $resourceID, $value, string $target){
        $resLocal =  $this->findOneBy(['id_acl_resource'=>$resourceID, 'id_acl_role'=>$roleID]);

        if($value){
            if(!$resLocal && $target === 'resourceID' ){
                $this->insert(['id_acl_resource'=>$resourceID, 'id_acl_role'=>$roleID]);
            }else if($resLocal && $target === 'privilegeID'){
                $this->updateBy(['id'=>$resLocal->id], ['privilege' => $value] );
            }
        }else if (  $target === 'resourceID' )  {
            if($resLocal){
                $this->delete($resLocal->id);
            }
        }
    }




}