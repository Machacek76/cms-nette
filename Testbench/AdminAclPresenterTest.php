<?php



require __DIR__ . '/bootstrap.php';

use Tester\Assert;





class AdminAclPresenterTest extends \Tester\TestCase {

    use \Testbench\TPresenter;


	protected $testUsers;


    public function testRenderAll (){

        /** ============================================= */		
		/**  not login user  */
		/** ============================================= */
		$this->checkRedirect('Admin:Acl:all', '/admin/sign/in/');
		$this->testUsers = $this->getPresenter()->context->parameters['testUsers'];
        /** ============================================= */
		/** test user no access */
		/** ============================================= */
        $this->login($this->testUsers['user']['data']['id'], $this->testUsers['user']['role'], $this->testUsers['user']['data'] );        
        $this->checkRedirect('Admin:Acl:all', '/admin/');
        $this->logOut();
        /** ============================================= */
		/** test admin with access */
		/** ============================================= */
        $this->login($this->testUsers['admin']['data']['id'], $this->testUsers['admin']['role'], $this->testUsers['admin']['data'] );        
        $response = $this->checkAction('Admin:Acl:all');
        $dom = @\Tester\DomQuery::fromHtml($response->getSource()); // @ - not valid HTML
        Assert::true( $dom->has('input[name="data"]') );
        Assert::true( $dom->has('input[name="target"]') );
        $this->logOut();

    }



    public function testRenderAllow (){

        /** ============================================= */		
		/**  not login user  */
		/** ============================================= */
		$this->checkRedirect('Admin:Acl:allow', '/admin/sign/in/', ['id'=>1]);
		$this->testUsers = $this->getPresenter()->context->parameters['testUsers'];
        /** ============================================= */
		/** test user no access */
		/** ============================================= */
        $this->login($this->testUsers['user']['data']['id'], $this->testUsers['user']['role'], $this->testUsers['user']['data'] );        
        $this->checkRedirect('Admin:Acl:allow', '/admin/', ['id'=>1]);
        $this->logOut();
        /** ============================================= */
		/** test admin with access */
		/** ============================================= */
        $this->login($this->testUsers['admin']['data']['id'], $this->testUsers['admin']['role'], $this->testUsers['admin']['data'] );        
        $response = $this->checkAction('Admin:Acl:all', ['id'=>1]);
        $this->logOut();

    }



}






(new AdminAclPresenterTest())->run();





