<?php


require __DIR__ . '/bootstrap.php';

use Tester\Assert;

class AdminSignPresenterTest  extends \Tester\TestCase {
    

	use \Testbench\TPresenter;

    /** @var testusers */
    public $testUsers;

    public function testRenderSignIn (){

        $response = $this->checkAction('Admin:Sign:in', [], ['password'=>'55486321', 'username'=>'fakeUser'] );
        $dom = @\Tester\DomQuery::fromHtml($response->getSource()); // @ - not valid HTML
        Assert::true( $dom->has('input[name="username"]') );
        Assert::true( $dom->has('input[name="password"]') );

        $this->checkForm('Admin:Sign:in', 'signInForm', ['password'=>'55486321', 'username'=>'testuser'], '/admin/');

        $this->logOut();

        $this->testUsers = $this->getPresenter()->context->parameters['testUsers'];
        /** ============================================= */
		/** test user no access */
        /** ============================================= */
        $this->login($this->testUsers['user']['data']['id'], $this->testUsers['user']['role'], $this->testUsers['user']['data'] );  
        $this->checkRedirect('Admin:Sign:in', '/admin/'); 
        $this->logOut();
        
        /** ============================================= */
		/** test admin with access */
		/** ============================================= */
        $this->login($this->testUsers['admin']['data']['id'], $this->testUsers['admin']['role'], $this->testUsers['admin']['data'] );  
        $this->checkRedirect('Admin:Sign:in', '/admin/'); 
        $this->logOut();
    }




}

(new AdminSignPresenterTest())->run();

