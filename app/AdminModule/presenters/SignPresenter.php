<?php

namespace App\AdminModule\Presenters;

use App\Forms;

use Nette\Application\UI\Form;


class SignPresenter extends BasePresenter{



	/** @persistent */
	public $backlink = '';

	/** @var Forms\SignInFormFactory */
	private $signInFactory;

	/** @var Forms\SignUpFormFactory */
	private $signUpFactory;


	public function __construct(Forms\SignInFormFactory $signInFactory, Forms\SignUpFormFactory $signUpFactory){

		$this->signInFactory = $signInFactory;
		$this->signUpFactory = $signUpFactory;
		
	}




	public function renderIn (){
		//$this->flashMessage('Danger', 'danger');
		if($this->user->isLoggedIn()){
			$this->redirect('Homepage:');
		}
	}



	/**
	 * Sign-in form factory.
	 * @return Form
	 */
	protected function createComponentSignInForm()
	{
		return $this->signInFactory->create(function () {
			$this->restoreRequest($this->backlink);
			$this->redirect('Homepage:');
		});
	}




	public function actionOut()
	{
		$this->getUser()->logout();
	}
}
