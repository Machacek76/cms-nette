<?php

namespace App\Forms;

use App\Model;
use Nette;
use Nette\Application\UI\Form;


class SignUpFormFactory
{
	use Nette\SmartObject;

	const PASSWORD_MIN_LENGTH = 8;

	/** @var FormFactory */
	private $factory;

	/** @var Model\UserManager */
	private $userManager;

	/** @var Model\AclRole\Model */
	private $aclRoleModel;

	/** @var \Nette\Localization\ITranslator */
	private $translator;

	/** @var  array */
	private $roles;

	/** @var Model\UserRoleModel */
	private $userRoleModel;


	/** @var Model\UserModel */
	private $userModel;


	public function __construct(FormFactory $factory, 
								Model\UserManager $userManager,
								Model\AclRoleModel $aclRoleModel,
								Model\UserRoleModel $userRoleModel,
								Model\UserModel $userModel,
								\Nette\Localization\ITranslator $translator
								)
	{
		$this->factory = $factory;
		$this->userManager = $userManager;
		$this->translator = $translator;
		$this->aclRoleModel = $aclRoleModel;
		$this->userRoleModel = $userRoleModel;
		$this->userModel = $userModel;
		$this->roles = $this->aclRoleModel->getRole('all');
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->addText('username', $this->translator->translate('admin.form.enterUsername'))
			->setRequired($this->translator->translate('admin.form.requietUsername'));

		$form->addText('name', $this->translator->translate('admin.form.enterName'))
			->setRequired($this->translator->translate('admin.form.requietName'));

		$form->addText('email', $this->translator->translate('admin.form.enterEmail'))
			->setRequired(true)
			->addRule(Form::EMAIL, $this->translator->translate('admin.form.notValidEmail'));

		$form->addPassword('password', $this->translator->translate('admin.form.enterPassword'))
			->setOption('description', $this->translator->translate('admin.form.lenghtPassword', ['lenght'=>self::PASSWORD_MIN_LENGTH]))
			->setRequired($this->translator->translate('admin.form.reguietPassword'))
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH)
			->addRule(Form::PATTERN, $this->translator->translate('admin.form.passwordLover'), '.*[a-z].*')
			->addRule(Form::PATTERN, $this->translator->translate('admin.form.passowrdUper'), '.*[A-Z].*')
			->addRule(Form::PATTERN, $this->translator->translate('admin.form.paswordNumber'), '.*[0-9].*');
			
		$form->addPassword('password_repeat', $this->translator->translate('admin.form.repeatPassword') )
			->setRequired($this->translator->translate('admin.form.reguietPassword'))
			->addRule(Form::EQUAL, $this->translator->translate('admin.form.notMatchPassword'), $form['password']);

		$form->addCheckbox('status', $this->translator->translate('admin.settings.user.statusForm'));
			foreach ($this->roles as $role) {
				if ($role->id > 1){
					$form->addCheckbox('role_' . $role->id, $this->translator->translate('admin.settings.role.'.$role->role));
				}
			}




		$form->addSubmit('send', $this->translator->translate('admin.form.save'));

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				//($username, $name, $email, $password, $status)
				$this->userManager->add($values->username, $values->name, $values->email, $values->password, $values->status);

				$res = $this->userModel->getUser(['username'=>$values->username]);

				foreach ($values as $key => $val) {
					if(strpos($key, 'role_') === 0){
						if($val) {
							$arr = explode('_', $key);
							$this->userRoleModel->insert(['id_user'=>$res->id, 'id_acl_role'=>$arr[1]]);      
							unset($array);
						}
					}
				}
			} catch (Model\DuplicateNameException $e) {
				$form['username']->addError($this->translator->translate('admin.form.takenUsername'));
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
