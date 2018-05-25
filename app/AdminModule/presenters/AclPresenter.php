<?php

namespace App\AdminModule\Presenters;


use App\Forms;

use Nette\Application\UI\Form;


class AclPresenter extends BasePresenter{


    /** @var Forms\AclAddResourceFactory */
	private $aclAddResourceFactory;

	/** @var Forms\AclAddRoleFactory */
    private $aclAddRoleFactory;
    
	/** @var Forms\AclEditAllowFactory */
    private $aclEditAllowFactory;



    /** @var roleID */
    private $roleID;


	public function __construct(Forms\AclAddResourceFactory $aclAddResourceFactory, Forms\AclAddRoleFactory $aclAddRoleFactory, Forms\AclEditAllowFactory $aclEditAllowFactory)	{
		$this->aclAddResourceFactory = $aclAddResourceFactory;
		$this->aclAddRoleFactory = $aclAddRoleFactory;
		$this->aclEditAllowFactory = $aclEditAllowFactory;
		
	}

    public function renderAll (){


        /** delete resource or role */
        if($this->httpRequest->getQuery('act') === 'remove' ){
            $target = $this->httpRequest->getQuery('target');
            $id = (int)$this->httpRequest->getQuery('_id');
            
            if($target === 'resource' && $id > 0){
                $this->context->getService('aclResourceModel')->delete($id);
                $this->context->getService('aclAllowModel')->findAll()->where('id_acl_resource = ?', $id)->delete();
            }else if($target === 'role' && $id > 0){
                $this->context->getService('aclRoleModel')->findAll()->where('id_acl_role = ?', $id)->delete();
            }
        }




        $res = $this->context->getService('aclRoleModel')->findAll();
        $rows = $res->fetchAll();
        foreach($rows as $row){
            $this->root['content']['aclRoles'][] = $row->toArray();
        }

        $res = $this->context->getService('aclResourceModel')->findAll();
        $rows = $res->fetchAll();
        foreach($rows as $row){
            $arr = $row->toArray();
            $arr['resourceLink']  = \str_replace('Admin:', '', $arr['resource']); 
            $this->root['content']['aclResource'][] = $arr;
            unset($arr);
        }

    }


    public function renderAllow( int $id = 0){
        $this->roleID = $id;

        if($this->roleID === 4 ){
            $this->flashMessage($this->translator->translate('admin.settings.role.permissionDenied', ['id'=>$this->roleID]), 'danger');
            $this->redirect('Acl:all');
        }

        $res = $this->context->getService('aclRoleModel')->findOneBy(['id'=>$id]);
        if($res){
            $this->root['content']['role'] = $res->toArray();
        }else{
            $this->flashMessage($this->translator->translate('admin.settings.role.roleNotFound', ['id'=>$this->roleID]), 'danger');
            $this->redirect('Acl:all');
            
        }
    }



   /**
	 * Acl-Allow-edit form factory.
	 * @return Form
	 */
	protected function createComponentAclEditAllowForm(){
        if($this->roleID === NULL){
            $this->roleID = -1;
        }
 		return $this->aclEditAllowFactory->create(function () {
		//	$this->redirect('Homepage:');
		}, $this->roleID);
	}
    
    /**
	 * Acl-add-resource form factory.
	 * @return Form
	 */
	protected function createComponentAclAddResourceForm(){

		return $this->aclAddResourceFactory->create(function () {
		//	$this->redirect('Homepage:');
		});
	}
    
    /**
	 * Acl-add-role form factory.
	 * @return Form
	 */
	protected function createComponentAclAddRoleForm(){
  
		return $this->aclAddRoleFactory->create(function () {
		//	$this->redirect('Homepage:');
		});
	}
}





