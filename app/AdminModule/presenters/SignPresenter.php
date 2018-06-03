<?php

namespace App\AdminModule\Presenters;

use App\Forms;

use App\Model;

use Nette\Application\UI\Form;

use App\Components\Mailer\Mailer;

class SignPresenter extends BasePresenter{



	/** @persistent */
	public $backlink = '';

	/** @var Forms\SignInFormFactory */
	private $signInFactory;

	/** @var Forms\SignForgotFormFactory */
	private $signForgotFormFactory;

	/** @var Forms\SignResetFormFactory */
	private $signResetFormFactory;

    /** @var App\Components\Mailer */
	private $mailer;

	/** @var Model\UserManaer */
	private $userManager;




	public function __construct(Forms\SignInFormFactory $signInFactory, 
								Forms\SignForgotFormFactory $signForgotFormFactory,
								Forms\SignResetFormFactory $signResetFormFactory,
								\App\Components\Mailer\Mailer $mailer,
								Model\UserManager $userManager
								){

		$this->signInFactory = $signInFactory;
		$this->signForgotFormFactory = $signForgotFormFactory;
		$this->mailer               = $mailer;
		$this->signResetFormFactory = $signResetFormFactory;
		$this->userManager = $userManager;
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
	protected function createComponentSignInForm()	{
		return $this->signInFactory->create(function () {
			$this->restoreRequest($this->backlink);
			$this->redirect('Homepage:');
		});
	}


	public function renderReset(string $id = NULL) {
		if($this->user->isLoggedIn()){
			$this->redirect('Homepage:default');
		}

		$res = $this->context->getService('userMetaModel')->findOneBy(['meta'=>$id]);
		
		if(!$res){
			$this->flashMessage( $this->translator->translate('admin.signIn.tokenNotFount'), 'danger' );
			$this->redirect('Sign:forgot');
		}else if ((int)$res->value < time()){
			$this->flashMessage( $this->translator->translate('admin.signIn.tokenTimeOut'), 'danger' );
			$this->redirect('Sign:forgot');
		}
		$this->token->value = $id;
	}

	public function renderForgot () {
		if($this->user->isLoggedIn()){
			$this->redirect('Homepage:default');
		}
	}


	protected function createComponentSignForgotForm (){
		
		return $this->signForgotFormFactory->create(function($values){
			$this->flashMessage($this->translator->translate('admin.signIn.sendMail', [ 'email' => $values['email']]), 'success' );

			$token = bin2hex(random_bytes(32));
			$link = $this->link( '//Sign:reset', ['id'=>$token] );

			$this->context->getService('userMetaModel')->set ($values['id'], 'forgot-password', $token, time()+ 60 *60 );
			$this->mailer->send( $values['email'], $this->translator->translate('admin.signIn.sendMailSub'), $this->translator->translate('admin.signIn.sendMailMessage', ['link'=>$link]) );
			$this->redirect('Sign:in');
		});
	}


	protected function createComponentSignResetForm(){
		return $this->signResetFormFactory->create(function($values){
			$this->flashMessage($this->translator->translate('admin.signIn.passwordHasReset'));

			$res = $this->context->getService('userMetaModel')->findOneBy(['meta'=>$this->token->value]);
		
			if(!$res){
				$this->flashMessage( $this->translator->translate('admin.signIn.tokenNotFount', 'danger') );
				$this->redirect('Sign:forgot');
			}else if ((int)$res->value < time()){
				$this->flashMessage( $this->translator->translate('admin.signIn.tokenTimeOut', 'danger') );
				$this->redirect('Sign:forgot');
			}
			$this->userManager->resetPassword($values->password, $res->id_users);
		
			$this->redirect('Sign:in');
		}, NULL );
	}
	


	public function actionOut(){
		$this->getUser()->logout();
	}
}
