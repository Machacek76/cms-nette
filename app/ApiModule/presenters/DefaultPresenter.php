<?php




namespace App\ApiModule\Presenters;

use Nette;



/**
 * Base presenter for all application presenters.
 */
class DefaultPresenter extends \App\Presenters\BasePresenter{
    
    

    public function renderDefault(){
        $this->sendPayload();
    }



}


