<?php

namespace App\ApiModule\Presenters;
      
use Nette\Utils\Image;



class ImageFlowPresenter extends BasePresenter {



    public $flowUploader;












    public function actionUpload(){
        $this->sendJson = false;


        $this->flowUploader = $this->context->getService('flowUploader');


        $data = $this->flowUploader->upload($this->httpRequest->getMethod());


        if(isset($data['header'])){
            $this->httpResponse->setCode(\Nette\Http\Response::$data['header']);
        }


        if ($this->httpRequest->getMethod() === 'POST' && $data['filepath']) {
            
            $image = Image::fromFile( $data['filepath'] );
            $imageRow = [
                'pubdate'       => date('Y-m-d h:i:s'),
                'name'          => $this->httpRequest->getPost('flowChunkNam'),
                'alt'           => $this->httpRequest->getPost('flowChunkAlt'),
                'description'   => $this->httpRequest->getPost('flowChunkDes'),
                'author'        => $this->httpRequest->getPost('flowChunkAut'),
                'uploader'      => $this->user->getIdentity()->data['name'],
                'path'          => str_replace($this->context->parameters['wwwDir'], "", $data['filepath']),
                'width'         => $image->getWidth(),
                'height'        => $image->getHeight(),
                'mime'          => mime_content_type ( $data['filepath'] )
            ];

            $this->context->getService('mediaModel')->saveMedia($imageRow);
        }

        exit;
    }




    
}





