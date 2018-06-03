<?php


namespace App\Forms;



use Nette;

use Nette\Application\UI\Form;

use Nette\Security\User;

use Kdyby\Translation\Translator;


class SignResetFormFactory {

    use Nette\SmartObject;
    
	const PASSWORD_MIN_LENGTH = 8;

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



    public function create(callable $onSuccess, $token = NULL){
        
		$form = $this->factory->create();
        
        if($token){
            $form->addHidden('token', $token);
        }

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
        
        $form->addSubmit('send', $this->translator->translate('admin.form.save'));


        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			$onSuccess($values);
		};

		return $form;
    }
    

}





