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


	public function __construct(FormFactory $factory, User $user, \Nette\Localization\ITranslator $translator){
		$this->factory 		= $factory;
		$this->user 		= $user;
		$this->translator	= $translator;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->addText('username', $this->translator->translate('admin.sigIn.user'))
			->setRequired($this->translator->translate('admin.sigIn.enterUsername'));

		$form->addPassword('password', $this->translator->translate('admin.sigIn.password'))
			->setRequired($this->translator->translate('admin.sigIn.enterPassword'));

		$form->addCheckbox('remember', $this->translator->translate('admin.sigIn.keepSigned'));

		$form->addSubmit('send', $this->translator->translate('admin.sigIn.login'));

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
				$this->user->login($values->username, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError($this->translator->translate('admin.sigIn.errorLogin') );
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
