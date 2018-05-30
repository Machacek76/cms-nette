<?php

namespace App\ApiModule\Presenters;






class UserPresenter extends BasePresenter{





    public function actionGet ($id = 'all') {


        $data = NULL;
        $privilege = NULL;

        $privilege =  $this->getPrivilege('Api:User:get', 'putAll');
        if($privilege){
            $data = $this->context->getService('userModel')->getApiUser($id);
        }

        $privilege =  $this->getPrivilege('Api:User:get', 'getAll');
        if($privilege){
            $data = $this->context->getService('userModel')->getApiUser($id);
        }

        $id = (int)$id;
        if($id === $this->user->id){
            $data = $this->context->getService('userModel')->getApiUser($id);
            $privilege = true;
        }

        if(!$privilege){
            $this->sendError('403');
        }else if(!$data){
            $this->sendError('404');
        }else{
            $this->payload->data = $data;
        }
    }


    public function actionPut (){
        $this->payload->data['ng'] = $this->ngData;
    }



    
}



