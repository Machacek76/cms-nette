<?php


namespace App\Forms;



use Nette;

use Nette\Application\UI\Form;

use Nette\Security\User;

use Kdyby\Translation\Translator;


class ExampleFormFactory {

	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var translator */
    private $translator;
    
    public function __construct(FormFactory $factory,
        \Nette\Localization\ITranslator $translator, 
        \App\Model\AclRoleModel $aclRoleModel
        ){
        $this->factory 				= $factory;
        $this->aclRoleModel 	    = $aclRoleModel;
        $this->translator 			= $translator;
    }



    public function create(callable $onSuccess){
        
		$form = $this->factory->create();

		$form->addText('data', $this->translator->translate('admin.settings.role.labelFormData'))
			->setRequired($this->translator->translate('admin.settings.role.labelFormRequired'));


        $form->addSubmit('send', $this->translator->translate('admin.settings.role.labelSave'));
        
        
        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			
			$onSuccess($values);
		};

		return $form;
	}

}





