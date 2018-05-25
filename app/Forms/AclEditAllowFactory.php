<?php

namespace App\Forms;



use Nette;

use Nette\Application\UI\Form;

use Nette\Security\User;

use Kdyby\Translation\Translator;


class AclEditAllowFactory{

	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var translator */
	private $translator;

	/** @var \App\Model\AclResourceModel */
	private $aclResourceModel;

	/** @var \App\Model\AclRoleModel */
	private $aclRoleModel;

	/** @var \App\Model\AclAllowModel */
	private $aclAllowModel;

	public function __construct(FormFactory $factory,
								 \Nette\Localization\ITranslator $translator, 
								 \App\Model\AclResourceModel $aclResourceModel,
								 \App\Model\AclRoleModel $aclRoleModel,
								 \App\Model\AclAllowModel $aclAllowModel
								 ){
		$this->factory 				= $factory;
		$this->aclResourceModel 	= $aclResourceModel;
		$this->aclRoleModel 		= $aclRoleModel;
		$this->aclAllowModel 		= $aclAllowModel;
		$this->translator 			= $translator;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess, int $roleID)
	{


		$form = $this->factory->create();

		$res = $this->aclResourceModel->findAll();
		$resources = $res->fetchAll();


		foreach($resources as $resource){
			$check = $this->aclAllowModel->getAllow($resource->id, $roleID);

			$form->addCheckbox("allowID_" . $resource->id, $resource->resource)
			->setDefaultValue($check)
			->setAttribute('class', 'label-success');
		}

		$form->addHidden('roleID')->setDefaultValue($roleID);


		$form->addSubmit('send', $this->translator->translate('admin.settings.resource.labelSave'));

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {

			$roleID = (int)$values->roleID;
			foreach($values as $key=>$value){
				$arr = explode('_', $key);

				if (isset($arr[1])){
					$resourceID = (int)$arr[1];
					$this->aclAllowModel->setAllow($roleID, $resourceID, $value);
				}
			}
			$onSuccess();
		};

		return $form;
	}












}



