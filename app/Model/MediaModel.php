<?php




namespace App\Model;






class MediaModel extends BaseModel{




    public $tableName = 'media';





    public function saveMedia (array $row ){

        $res = $this->findOneBy(['path'=>$row['path']]);

        if($res){
            return $this->updateBy(['path'=>$row['path']], $row);
        }else{
            return $this->insert($row);
        }
    }





    
}



