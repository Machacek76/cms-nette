<?php

namespace App\Forms;



use Nette;

use Nette\Application\UI\Form;

use Nette\Security\User;

use Kdyby\Translation\Translator;


class AclAddResourceFactory{

	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var translator */
	private $translator;

	/** @var \App\Model\AclResourceModel */
	private $aclResourceModel;

	public function __construct(FormFactory $factory,
								 \Nette\Localization\ITranslator $translator, 
								 \App\Model\AclResourceModel $aclResourceModel
								 ){
		$this->factory 				= $factory;
		$this->aclResourceModel 	= $aclResourceModel;
		$this->translator 			= $translator;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();

		$form->addText('data', $this->translator->translate('admin.settings.resource.labelFormData'))
			->setRequired($this->translator->translate('admin.settings.resource.labelFormRequired'));

		$form->addText('target', $this->translator->translate('admin.settings.resource.labelFormTarget'))
			->setRequired($this->translator->translate('admin.settings.resource.labelFormRequired'));


		$form->addSubmit('send', $this->translator->translate('admin.settings.resource.labelSave'));

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {


			/** check is resource exist */
			$res = $this->aclResourceModel->findBy(['resource'=>$values->data, 'target'=>$values->target]);
			$res = $res->fetch();

			if($res){
				$form->addError( $this->translator->translate('admin.settings.resource.inccorrectResource') );
			}else{
				$this->aclResourceModel->insert(['resource'=>$values->data, 'target'=>$values->target]);
			}

			$onSuccess();
		};


		return $form;
	}












}



