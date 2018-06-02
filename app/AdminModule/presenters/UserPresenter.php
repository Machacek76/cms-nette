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

    /** @var Forms\SignUpFormFactory */
    public $signUpFactory;




    /**
     * Undocumented function
     *
     * @param Forms\UserEditFactory $userEditFactory
     */
	public function __construct(Forms\UserEditFactory $userEditFactory, Forms\SignUpFormFactory $signUpFactory){
        $this->userEditFactory = $userEditFactory;	
        $this->signUpFactory = $signUpFactory;
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
        $this->checkAccess($id, 'Admin:User:all');

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
        }

        $this->setEditUser($res['id']);

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
        $this->checkAccess($id, 'Admin:User:all');

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



    public function renderAdd () {

    }


	/**
	 * User edit form factory.
	 * @return Form
	 */
	protected function createComponentSignUpForm(){
        $this->checkAccess($this->user->id, 'Admin:User:add');

		return $this->signUpFactory->create(function () {
            $this->redirect('User:all');
		});
	}


	/**
	 * User edit form factory.
	 * @return Form
	 */
	protected function createComponentUserEditForm(){

        if(!$this->editUser){
            $this->setEditUser((int)$this->httpRequest->getPost('id') );
        }


		return $this->userEditFactory->create(function ($values) {
            $this->checkAccess($values->id, 'Admin:User:all');
            
            if($values->id != -1){

                $newUser['id'] = $values->id;
                $newUser['email'] = $values->email;

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
                
                if($this->user->isAllowed('Admin:User:all')){
                    $newUser['status'] = $values->status == false ? 0 : 1;
                    $this->context->getService('userRoleModel')->findAll()->where('id_user = ?', $values->id )->delete();
                    foreach ($values as $key => $val) {
                        if(strpos($key, 'role_') === 0){
                            if($val) {
                                $arr = explode('_', $key);
                                $this->context->getService('userRoleModel')->insert(['id_user'=>$values->id, 'id_acl_role'=>$arr[1]]);      
                                unset($array);
                            }
                        }
                    }
                }

                $this->saveEditUser = TRUE;
            }
            
		}, $this->editUser);
	}







    public function setEditUser (int $userId){
        

        $res = $this->context->getService('userModel')->getApiUser($userId);

        if(!$this->user->isAllowed('Admin:User:all')){
            unset($res['status']);
        }else{
            $roles = $this->context->getService('userRoleModel')->getRoles($userId, TRUE);
            
            foreach ($roles as $role){
                $res['role_' . $role['id_acl_role']] = 1;
            }
        }
        
        $this->editUser = $res;
    }




}


