<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Kdyby\Translation\Translator;


class SignInFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;

	/** @var translator */
	private $translator;

	/** @var \App\Model\UserModel */
	private $userModel;


	/** @var \App\Model\UserMetaModel */
	private $userMetaModel;

	/** @var  \GlueWork\glCache\glCacheExtension  */
	private $glCache;

	public function __construct(FormFactory $factory, 
								User $user,
								 \Nette\Localization\ITranslator $translator, 
								 \App\Model\UserModel $userModel, 
								 \App\Model\UserModel $userMetaModel,
								 \GlueWork\glCache\glCacheExtension  $glCache
								 ){
		$this->factory 			= $factory;
		$this->user 			= $user;
		$this->translator		= $translator;
		$this->userModel 		= $userModel;
		$this->userMetaModel	= $userMetaModel;
		$this->glCache			= $glCache;
		/* vyresit cachovani  */
		$this->glCache->initCache(['tempDir'=>__DIR__ . "/../../temp/_glCache", 'name'=>'glCache']);
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->addText('username', $this->translator->translate('admin.signIn.user'))
			->setRequired($this->translator->translate('admin.signIn.enterUsername'));

		$form->addPassword('password', $this->translator->translate('admin.signIn.password'))
			->setRequired($this->translator->translate('admin.signIn.enterPassword'));

		$form->addCheckbox('remember', $this->translator->translate('admin.signIn.keepSigned'))
		->setAttribute('class', 'label-success');

		$form->addSubmit('send', $this->translator->translate('admin.signIn.login'));

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {

			/**
			 * check is user exist
			 */
			$glc = 0; // count fail login for last hour
			$user = $this->userModel->getUser(['username'=>$values->username], ['id','status']);
			if(!$user){
				$form->addError( $this->translator->translate('admin.signIn.inccorrectName') );
				return;
			}else if ($user->status === 0){
				$form->addError( $this->translator->translate('admin.signIn.blockAccount') );
				return;
			}else{ // check count fail login;
				$this->glCache->nocache	= false;		
				$glc = $this->glCache->loadCache('fail-login-userId-' . $user->id);
				$glc = !$glc ? 0 : $glc;
				$glc++;
				if($glc > 3 ) {
					$form->addError( $this->translator->translate('admin.signIn.maxCountFailLogin') );
					return;
				}	
			}


			try {
				$this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
				$this->user->login($values->username, $values->password);
				$this->glCache->saveCache( 'fail-login-userId-' . $user->id, 0, '60 minutes');

			} catch (Nette\Security\AuthenticationException $e) {
				$this->glCache->saveCache( 'fail-login-userId-' . $user->id, $glc, '60 minutes');
				$form->addError( $this->translator->translate('admin.signIn.inccorrectPassword') );
				return;
			}

			$onSuccess();
		};

		return $form;
	}
}
