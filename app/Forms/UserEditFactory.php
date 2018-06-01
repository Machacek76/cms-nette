<?php

namespace App\Forms;




use App\Model;

use Nette;

use Nette\Application\UI\Form;


class UserEditFactory {
    
	use Nette\SmartObject;
    
	/** @var FormFactory */
	private $factory;


	/** @var \App\Model\AclRoleModel */
	private $aclRoleModel;

	/** @var \Nette\Localization\ITranslator */
	private $translator;

	/** @var $roles */
	private $roles;

	public function __construct(FormFactory $factory,
								\Nette\Localization\ITranslator $translator, 
								\App\Model\AclRoleModel $aclRoleModel
								)
	{

		$this->aclRoleModel = $aclRoleModel;
		$this->factory = $factory;
		$this->translator = $translator;

		$this->roles = $this->aclRoleModel->getRole('all');
	}



	/**
	 * @return Form
	 */
	public function create(callable $onSuccess, array $user = NULL){



        $form = $this->factory->create();

		$form->addText('username', $this->translator->translate('admin.settings.user.userNameForm'))->setDisabled(true);
		
		if($user['id'] === -1){
			$form->addHidden('id', $this->translator->translate('admin.settings.user.userIdForm'));		
		}else{
			$form->addText('userId', $this->translator->translate('admin.settings.user.userIdForm'))->setDisabled(true);
			$form->addHidden('id', $this->translator->translate('admin.settings.user.userIdForm'));	
		}

		$form->addText('email', $this->translator->translate('admin.settings.user.userEmailForm'))
			->setRequired(TRUE)
			->addRule(Form::EMAIL, $this->translator->translate('admin.form.notValidEmail'));

		if( isset($user['status'])  ){
			$form->addCheckbox('status', $this->translator->translate('admin.settings.user.statusForm'));
		}
		
		if($user){
			$user['userId'] = $user['id'];
			$form->setDefaults($user);
		}
		
		$form->addPassword('password', $this->translator->translate('admin.settings.user.changePsw'));

		$form->addSubmit('send', $this->translator->translate('admin.settings.user.save'));

        
        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			
			$onSuccess($values);
		};
		

		return $form;
    }



}


