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

		$res = $this->aclResourceModel->findAll()->order('target');
		$resources = $res->fetchAll();


		foreach($resources as $resource){
			$check = $this->aclAllowModel->getAllow($resource->id, $roleID);

			$resCheck = $check ? TRUE : FALSE;
			$priCheck = $check ? $check->privilege : 'none';

			$form->addCheckbox("resourceID_" . $resource->id, $resource->resource)
			->setDefaultValue($resCheck)
			->setAttribute('class', 'label-success');


			if($resource->target === 'Api'){

				$form->addSelect('privilegeID_' . $resource->id, $this->translator->translate('admin.settings.privilege.label'), [
					'getCurrent' => $this->translator->translate('admin.settings.privilege.labelCurrent'),
					'getAll' => $this->translator->translate('admin.settings.privilege.labelAll'),
					'none' => $this->translator->translate('admin.settings.privilege.labelNone')
				])->setDefaultValue($priCheck);
			}


		}

		$form->addHidden('roleID')->setDefaultValue($roleID);


		$form->addSubmit('send', $this->translator->translate('admin.settings.resource.labelSave'));

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {

			$roleID = (int)$values->roleID;
			
			$resourceArr = [];
			$privilegeArr = [];

			foreach($values as $key=>$value){
				
				if(strpos($key, 'privilegeID') === 0){
					$privilegeArr[] = ['key'=>explode('_', $key), 'value'=>$value];
				}else if(strpos($key, 'resourceID') === 0){
					$resourceArr[] = ['key'=>explode('_', $key), 'value'=>$value];
				}
			}

			/** first save resource */
			$this->saveAllow ($roleID, $resourceArr);
			$this->saveAllow ($roleID, $privilegeArr);
			$onSuccess();
		};

		return $form;
	}


	public function saveAllow ($roleID, $data){

		foreach ($data as $item){
			$this->aclAllowModel->setAllow( $roleID, $item['key'][1], $item['value'], $item['key'][0] );
		}
	}









}



