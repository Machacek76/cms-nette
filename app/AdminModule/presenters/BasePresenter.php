<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Admin\Model;
use App\Components\Mailer;


/**
 * Base presenter for all application presenters.
 */
 class BasePresenter extends \App\Presenters\BasePresenter{
        
    
    /** var @root */
    public $root;


	public function startup() {
		parent::startup();
		
		$this->context->getService('checkTables')->getTables();
	
		$this->glCache = $this->context->getService('glCache');
		$this->glCache->initCache($this->context->parameters['glCache']);
		$this->glCache->nocache = true;
		
		
		// test prihlaseni uzivatele
		if ($this->name != 'Admin:Sign') {
			if ($this->user->isLoggedIn()) {
				$user['email']				= $this->user->getIdentity()->data['email']; 
				$user['id']					= $this->user->getIdentity()->data['id']; 
				$user['role']				= ''; 
				$user['name']				= $this->user->getIdentity()->data['name']; 
				$user['avatar']				= $this->context->getService('userModel')->getAvatar($user['email']);
				$this->root['user']			= $user;
				$this->glCache->saveCache('user', $user);
				
			} else if($this->name === 'Admin:User' && ($this->action === 'forgot' || $this->action === 'reset' || $this->action === 'send' || $this->action === 'change' ) ){
				
			}else {
				$this->redirect('Sign:in', array(
					'backlink' => $this->storeRequest()
				));
			}
		}
		
		/*
		if(  !$this->user->isAllowed($this->presenter->name.':'.$this->presenter->view ) && $this->name !== 'Admin:Sign' ) {
			$this->flashMessage($this->translator->translate('ui.error.access_denied'), 'danger');
			$this->redirect('Homepage:default');
		}
		*/
	}



	/**
	 * Logout user
	 */
	public function handleLogout() {
		$this->user->logOut();
		$this->flashMessage('Byli jste odhlášeni.', 'success');
		$this->redirect('this');
	}

	
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