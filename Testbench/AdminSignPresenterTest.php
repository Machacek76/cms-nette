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

        $this->checkForm('Admin:Sign:in', 'signInForm', ['password'=>'zXFui;Js6&q#X6g', 'username'=>'testuser'], '/admin/');

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



    public function testRenderSignForgot (){

        $response = $this->checkAction('Admin:Sign:forgot' );
        $dom = @\Tester\DomQuery::fromHtml($response->getSource()); // @ - not valid HTML
        Assert::true( $dom->has('input[name="data"]') );

        $this->testUsers = $this->getPresenter()->context->parameters['testUsers'];
        /** ============================================= */
		/** test user no access */
        /** ============================================= */
        $this->login($this->testUsers['user']['data']['id'], $this->testUsers['user']['role'], $this->testUsers['user']['data'] );  
        $this->checkRedirect('Admin:Sign:forgot', '/admin/'); 
        $this->logOut();
        
        /** ============================================= */
		/** test admin with access */
		/** ============================================= */
        $this->login($this->testUsers['admin']['data']['id'], $this->testUsers['admin']['role'], $this->testUsers['admin']['data'] );  
        $this->checkRedirect('Admin:Sign:forgot', '/admin/'); 
        $this->logOut();
    }

    public function testRenderSignReset (){
        
        $this->checkRedirect('Admin:Sign:reset', '/admin/sign/forgot/', ['id'=>'fake-token-646874164']);
        $this->checkRedirect('Admin:Sign:reset', '/admin/sign/forgot/', ['id'=>'']);

        $this->testUsers = $this->getPresenter()->context->parameters['testUsers'];
        /** ============================================= */
		/** test user no access */
        /** ============================================= */
        $this->login($this->testUsers['user']['data']['id'], $this->testUsers['user']['role'], $this->testUsers['user']['data'] );  
        $this->checkRedirect('Admin:Sign:reset', '/admin/'); 
        $this->logOut();
        
        /** ============================================= */
		/** test admin with access */
		/** ============================================= */
        $this->login($this->testUsers['admin']['data']['id'], $this->testUsers['admin']['role'], $this->testUsers['admin']['data'] );  
        $this->checkRedirect('Admin:Sign:reset', '/admin/'); 
        $this->logOut();
    }


}

(new AdminSignPresenterTest())->run();

