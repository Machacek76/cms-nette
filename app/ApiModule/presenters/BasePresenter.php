<?php

namespace App\ApiModule\Presenters;

use Nette;


/**
 * Base presenter for all application presenters.
 */
 class BasePresenter extends \App\Presenters\BasePresenter{
    

    /**
     * var $ngData
     * data recive from agular requset
     */
    public $ngData  = [];


    
	public function startup() {
		parent::startup();
		
		if(  !$this->user->isAllowed($this->presenter->name.':'.$this->presenter->view ) ) {
			
			$this->sendError(403);
			$this->terminate;
		}
		$this->ngData = $this->getNgData();
	}

	/**
	 * Load and parse json POST dat
	 * @return type json
	 */
	public function getNgData (){
		$input	= file_get_contents('php://input');
		$ret	= json_decode($input);
		
		if(!$ret){
			return NULL;
		}else if(property_exists($ret, 'ng')){
			return $ret->ng;
		}else{
			return NULL;
		}
	}
	

	
	/**
	 * Function for send json status
	 *
	 * @param integer $code
	 * @return void
	 */
	public function sendError(int $code){

		switch ( $code ){
			case 200: $this->payload->data	= ['code'=>'E200', 'msg'=>'OK']; break; 
			case 400: $this->payload->error	= ['code'=>'E400', 'msg'=>'Bad Request']; break; 
			case 403: $this->payload->error = ['code'=>'E403', 'msq'=>'Forbiden'];	break;
			case 404: $this->payload->error = ['code'=>'E404', 'msq'=>'Not found'];	break;
			default:  $this->payload->error = ['code'=>$code];
		}

		$this->sendPayload();
	}



    
    /**
     * Send json output from api
     *
     * @return void
     */
    public function afterRender(){
		$this->sendPayload();
	}
    


 }