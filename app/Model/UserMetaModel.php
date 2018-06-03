<?php

namespace App\Model;

class UserMetaModel extends BaseModel{


    public $tableName = "user_meta";




    public function set (int $userID, $key, $meta, $value){
        $this->save(['id_users'=>$userID, 'key'=>$key, 'meta'=>$meta, 'value'=>$value], ['id_users'=>$userID, 'key'=>$key]);
    }


    public function get (int $userID, $key) {
        return $this->finOnedBy(['id_users'=>$userID, 'key'=>$key]);
    }


}