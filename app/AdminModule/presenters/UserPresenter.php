<?php

namespace App\AdminModule\Presenters;

use App\Forms;

use Nette\Application\UI\Form;


use Nette\Security\Passwords;

class UserPresenter extends BasePresenter  {
    


    /** @var $user */
    protected $editUser;

    /** @var $saveEditUser */
    protected $saveEditUser = FALSE;


    /** @var Forms\UserEditFactory */
    public $userEditFactory;

    /**
     * Undocumented function
     *
     * @param Forms\UserEditFactory $userEditFactory
     */
	public function __construct(Forms\UserEditFactory $userEditFactory){
		$this->userEditFactory = $userEditFactory;	
	}


    /**
     * Undocumented function
     *
     * @return void
     */
    public function renderAll(){

        $res = $this->context->getService('userModel')->findAll();

        $rows = $res->fetchAll();
        $users = [];
        $arr = [];

        foreach ($rows as $row){
            $arr = $row->toArray();
            unset($arr['password']);
            $arr['role'] = $this->context->getService('userRoleModel')->getRoles($row->id);
            $users[] = $arr;
            unset($arr);
        }

        $this->root['context']['users'] = $users;
    }



    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function renderEdit($id = NULL){
        $this->checkAccess($id);

        $res = $this->context->getService('userModel')->getApiUser($id);

        if(!$res){
            
            if($this->user->isInRole('admin')){
                // redirect amin to all the user
                $this->flashMessage($this->translator->translate('admin.settings.user.notFound', $this->id), 'danger');
                $this->redirect('User:all');
            }else{
                // redirect to current login the user
                $this->flashMessage($this->translator->translate('admin.settings.user.notFound', $this->id), 'danger');
                $this->redirect('User:get', ['id'=>$this->user->id]);
            }
        }else{
            // if not admin user, user not editing status
            if(!$this->user->isInRole('admin')){
                unset($res['status']);
            }
            $this->editUser = $res;
        }


        if($this->saveEditUser) {
            $this->redirect('User:get', ['id'=>$this->id]);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function renderGet($id = NULL){
        $this->checkAccess($id);

        $res = $this->context->getService('userModel')->getApiUser($id);

        if(!$res){
            if($this->user->isInRole('admin')){
                $this->flashMessage($this->translator->translate('admin.settings.user.notFound', $this->id), 'danger');
                $this->redirect('User:all');
            }else{
                $this->flashMessage($this->translator->translate('admin.settings.user.notFound', $this->id), 'danger');
                $this->redirect('User:get', ['id'=>$this->user->id]);
            }
        }

        // response to template user
        $arr = $res;
        $arr['role'] = $this->context->getService('userRoleModel')->getRoles($this->id);
        $this->root['context']['user'] = $arr;
    }




	/**
	 * User edit form factory.
	 * @return Form
	 */
	protected function createComponentUserEditForm(){
        
        if(!$this->editUser){
            $res = $this->context->getService('userModel')->getApiUser((int)$this->httpRequest->getPost('id'));
            // if not admin user, user not editing status
            if(!$this->user->isInRole('admin')){
                unset($res['status']);
            }
            $this->editUser = $res;
        }



		return $this->userEditFactory->create(function ($values) {
            $this->checkAccess($values->id);
            
            if($values->id != -1){

                $newUser['id'] = $values->id;
                $newUser['email'] = $values->email;

                if( property_exists($values, 'status') ){
                    $newUser['status'] = $values->status == false ? 0 : 1;
                }
                
                if( strlen($values->password) > 0 ){
                    if( strlen($values->password > 8) ) {
                        $newUser['password'] = Passwords::hash($values->password);
                    }else{
                        $this->flashMessage ($this->translator->translate( 'admin.settings.user.lowPass'), 'danger');
                        $this->redirect('User:edit', ['id'=>$values->id]);
                        return NULL;
                    }
                }

                $this->context->getService('userModel')->update($newUser);
                
                $this->saveEditUser = TRUE;
            }
            



		}, $this->editUser);
	}





}


