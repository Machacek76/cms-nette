<?php

namespace App\Forms;



use Nette;

use Nette\Application\UI\Form;

use Nette\Security\User;

use Kdyby\Translation\Translator;


class AclAddRoleFactory{

	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var translator */
	private $translator;

	/** @var \App\Model\AclRoleModel */
	private $aclRoleModel;

	public function __construct(FormFactory $factory,
								 \Nette\Localization\ITranslator $translator, 
								 \App\Model\AclRoleModel $aclRoleModel
								 ){
		$this->factory 				= $factory;
		$this->aclRoleModel 	    = $aclRoleModel;
		$this->translator 			= $translator;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
        
		$form = $this->factory->create();

		$form->addText('data', $this->translator->translate('admin.settings.role.labelFormData'))
			->setRequired($this->translator->translate('admin.settings.role.labelFormRequired'));

		$form->addHidden('target', $this->translator->translate('admin.settings.role.labelFormTarget'))
			->setRequired($this->translator->translate('admin.settings.role.labelFormRequired'));


		$form->addSubmit('send', $this->translator->translate('admin.settings.role.labelSave'));

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {


			/** check is role exist */
			$res = $this->aclRoleModel->findBy(['role'=>$values->data]);
			$res = $res->fetch();
			if($res){
				$form->addError( $this->translator->translate('admin.settings.role.inccorrectRole') );
			}else{
				$this->aclRoleModel->insert(['role'=>$values->data]);
			}

			$onSuccess();
		};

		return $form;
	}










}



