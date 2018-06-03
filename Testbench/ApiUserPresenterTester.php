<?php 

require __DIR__ . '/bootstrap.php';

use Tester\Assert;


/**
 * @testCase
 */
class ApiUserPresenterTester extends \Tester\TestCase{

	use \Testbench\TPresenter;

	protected $testUsers;


	public function testRenderGet()	{

		/** ============================================= */		
		/**  not login user  */
		/** ============================================= */
		$json = $this->checkJson('Api:User:get', ['id'=>'all' ])->getPayload();
		Assert::true($json->error['code'] === 'E403');
		
		$this->testUsers = $this->getPresenter()->context->parameters['testUsers'];

		/** ============================================= */
		/** test current user */
		/** ============================================= */

		/** login user with out allow and privilege for all only current user */
		$this->login($this->testUsers['user']['data']['id'], $this->testUsers['user']['role'], $this->testUsers['user']['data'] );
		
		/** test All user */
		unset($json);
		$json = $this->checkJson('Api:User:get', ['id'=>'all' ])->getPayload();
		Assert::same($json->error['code'], 'E403');
		unset($json);

		$json = $this->checkJson('Api:User:get', ['id'=>$this->testUsers['user']['data']['id'] ])->getPayload();
		Assert::same($json->data['username'], 'TestUser');
		Assert::same($json->data['email'], 'testuser@example.com');
		Assert::same($json->data['name'], 'Test User');		
		unset($json);

		/** test another user */
		$json = $this->checkJson('Api:User:get', ['id'=>1])->getPayload();
		Assert::same($json->error['code'], 'E403');

		$this->logOut();

		/** ============================================= */
		/** login admin user with allow and privilege for all */
		/** ============================================= */
		$this->login($this->testUsers['admin']['data']['id'], $this->testUsers['admin']['role'], $this->testUsers['admin']['data'] );

		/** test All user */
		unset($json);
		$json = $this->checkJson('Api:User:get', ['id'=>'all' ])->getPayload();
		Assert::true(property_exists($json, 'data'));
		$json = $this->checkJson('Api:User:get')->getPayload();
		Assert::true(property_exists($json, 'data'));

		/** test current user */
		$json = $this->checkJson('Api:User:get', ['id'=>$this->testUsers['admin']['data']['id'] ])->getPayload();
		Assert::same($json->data['name'], 'Admin');
		Assert::true(isset( $json->data['email']) );
		Assert::same($json->data['username'], 'Admin');		
		unset($json);

		/** test another user */
		$json = $this->checkJson('Api:User:get', ['id'=>$this->testUsers['user']['data']['id'] ])->getPayload();
		Assert::same($json->data['name'], 'Test User');
		Assert::true(isset($json->data['email']));
		Assert::same($json->data['username'], 'TestUser');	
		unset($json);

		/** test nother user */
		$json = $this->checkJson('Api:User:get', ['id'=>-1 ])->getPayload();
		Assert::same($json->error['code'], 'E404');
		unset($json);

		$this->logOut();
	
	}


	public function testRenderPut () {

		$testData = '{ng:["test":"test key"]}';


		$json = $this->checkJson('Api:User:put', ['id'=>'all'], ['rawData'=>$testData]);

		dump($json);

//		Assert::true($json->error['code'] === 'E403');
		
		$this->testUsers = $this->__testbench_presenter->context->parameters['testUsers'];

	}





/*
    public function testRenderAdminModule (){
        $this->checkRedirect('Admin:Homepage:default', '/admin/sign/in/');
        $this->logIn(4, 'admin', ['email'=>'admin@example.com', 'id'=>4, 'name'=>'admin tester']);
		$this->checkAction('Admin:Homepage:default');
		$this->logOut();
    }
*/

}

(new ApiUserPresenterTester())->run();