<?php

namespace App\Components\Flow;

class FlowUploader{



    protected $config;

    protected $file;

    protected $uploadDir;

    protected $uploadData;


    public function __construct(string $uploadDir){
        $this->uploadDir = $uploadDir;
    }


    public function upload($method){

        $this->uploadData   = [];

        $this->config = new Config();
        $this->config->setTempDir('/tmp');

        $this->file = new File( $this->config );

        $this->uploadData['filepath']  = NULL;

        if ($method === 'POST') {
            
            if ($this->file->validateChunk()) {
                $this->file->saveChunk();
            } else {
                $this->uploadData['header'] = 'S400_BAD_REQUEST';
                die;
            }

        } else {
            
            if ($this->file->checkChunk()) {
                $this->uploadData['header'] = 'S200_OK';
            } else {
                $this->uploadData['header'] = 'S204_NO_CONTENT';
            } 
        }

        $this->uploadData['upload'] = NULL;

        if ($this->file->validateFile() && $this->file->request->getTotalChunks() === $this->file->request->getCurrentChunkNumber()) {

            $this->uploadData['upload'] = TRUE;
            
            $this->save();

        } else {

            $this->uploadData['progress'] =  $this->file->request->getCurrentChunkNumber();;
        }

        return $this->uploadData;
    }



    protected function save (){
        $path = $this->uploadDir;

        
		$path .= '/' . date('Y');
		$this->createDir($path);

		$path .= '/' . date('m');
		$this->createDir($path);

        $path .= '/' . $this->file->request->getFileName();

        $path = $this->checkFileExist($path);
        
        $this->uploadData['filepath'] = $path;
        $this->file->save( $path );
    }


    public function checkFileExist($file){
        $locFile = $file;
        $arr = \pathinfo($file);
        $cnt = 1;
        while ( file_exists($locFile)){
            $locFile = $arr['dirname'] . '/' . $arr['filename'] . '-' . $cnt . '.' . $arr['extension'];
            $cnt++;
        }
        return $locFile;
    }




	/**
	 * 
	 * @param type $dir
	 */
	private function createDir($dir) {
		if (!is_dir($dir)) {
			mkdir($dir, 0755);
		}
	}
    
}