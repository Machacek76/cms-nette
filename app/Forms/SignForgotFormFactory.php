<?php


namespace App\Forms;

use App\Model;

use Nette;

use Nette\Application\UI\Form;

use Nette\Security\User;

use Kdyby\Translation\Translator;




class SignForgotFormFactory {

	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var translator */
    private $translator;

    /** @var Model\UserManager */
    private $userManager;
    

    
    public function __construct(FormFactory $factory,
        \Nette\Localization\ITranslator $translator, 
        \App\Model\UserManager $userManager
        ){
        
        $this->factory 				= $factory;
        $this->translator 			= $translator;
        $this->userManager          = $userManager;
    }



    public function create(callable $onSuccess){
        
		$form = $this->factory->create();

		$form->addText('data', $this->translator->translate('admin.form.forgotUser'))
			->setRequired($this->translator->translate('admin.form.requietForgotUser'));


        $form->addSubmit('send', $this->translator->translate('admin.form.send'));
        
        
        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {


            try {
                $user = $this->userManager->get($values->data);
            }catch (Model\UserNotFoundException $e) {
				$form['data']->addError($this->translator->translate('admin.form.notfoundUser', ['name' => $values->data ]));
                return;
            }
            
			$onSuccess($user->toArray());
		};

		return $form;
	}

}





