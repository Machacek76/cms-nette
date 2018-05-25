<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Admin\Model;
use App\Components\Mailer;


/**
 * Base presenter for all application presenters.
 */
 class BasePresenter extends \App\Presenters\BasePresenter{
        
    
    /** @var $root */
	public $root;
	
	/** @var $resource */
	public $resource;



	public function startup() {
		parent::startup();
		
		$this->context->getService('checkTables')->getTables();
	
		$this->glCache = $this->context->getService('glCache');
		$this->glCache->initCache($this->context->parameters['glCache']);
		$this->glCache->nocache = true;
		

		if ($this->user->isLoggedIn()) {
			$user['email']				= $this->user->getIdentity()->data['email']; 
			$user['id']					= $this->user->getIdentity()->data['id']; 
			$user['role']				= ''; 
			$user['name']				= $this->user->getIdentity()->data['name']; 
			$user['avatar']				= $this->context->getService('userModel')->getAvatar($user['email']);
			$this->root['user']			= $user;
			$this->glCache->saveCache('user', $user);
			
		}
	}

	/**
	 * Check authorization
	 * @return void
	 */
	public function checkRequirements($element){

		$this->resource = $this->name . ":" . $this->action;

		$this->root['system']['resource'] = $this->resource;
		
		if(!$this->user->authorizator->hasResource( $this->resource )){
			$this->redirect('Sign:in');
			return;
		}

	//	dump($this->resource);

		if (!$this->user->isAllowed($this->resource)){
			if (!$this->user->isLoggedIn()) {
				$this->redirect('Sign:in', ['backlink' => $this->storeRequest()]);
			} else {
				$this->flasHMessage($this->translator->translate('admin.signIn.permissionDenied'), 'danger');
				$this->redirect('Homepage:default');
			}
		}

	}



	/**
	 * Logout user
	 */
	public function handleLogout() {
		$this->user->logOut();
		$this->flashMessage($this->translator->translate('admin.signIn.logOut'), 'success');
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