<?php

namespace App\FrontModule\Presenters;

use Nette;


/**
 * Base presenter for all application presenters.
 */
 class BasePresenter extends \App\Presenters\BasePresenter{



	/** var @root */
	public $root;

    public function afterRender() {
		parent::afterRender();
		$this->template->root = $this->root;
		 
		/*
		$this->flashMessage('Danger', 'danger');
		$this->flashMessage('Info', 'info');
		$this->flashMessage('Warning', 'warning');
		$this->flashMessage('Succes', 'success');
		*/
    }
    
}


