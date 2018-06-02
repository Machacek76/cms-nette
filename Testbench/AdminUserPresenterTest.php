<?php


require __DIR__ . '/bootstrap.php';

use Tester\Assert;

class AdminUserPresenterTest  extends \Tester\TestCase {
    

	use \Testbench\TPresenter;



    public function testRenderUserAll (){


        /** ============================================= */		
		/**  not login user  */
		/** ============================================= */
		$this->checkRedirect('Admin:User:all', '/admin/sign/in/');
		$this->testUsers = $this->getPresenter()->context->parameters['testUsers'];
        /** ============================================= */
		/** test user no access */
		/** ============================================= */
        $this->login($this->testUsers['user']['data']['id'], $this->testUsers['user']['role'], $this->testUsers['user']['data'] );        
        $this->checkRedirect('Admin:User:all', '/admin/');
        $this->logOut();
        /** ============================================= */
		/** test admin with access */
		/** ============================================= */
        $this->login($this->testUsers['admin']['data']['id'], $this->testUsers['admin']['role'], $this->testUsers['admin']['data'] );        
        $response = $this->checkAction('Admin:User:all');
//        $dom = @\Tester\DomQuery::fromHtml($response->getSource()); // @ - not valid HTML
//        Assert::true( $dom->has('input[name="data"]') );
//        Assert::true( $dom->has('input[name="target"]') );
        $this->logOut();




    }



    public function testRenderUserGet () {
        /** ============================================= */		
		/**  not login user  */
		/** ============================================= */
		$this->checkRedirect('Admin:User:get', '/admin/sign/in/');
		$this->testUsers = $this->getPresenter()->context->parameters['testUsers'];
        /** ============================================= */
		/** test user no access */
        /** ============================================= */
        $this->login($this->testUsers['user']['data']['id'], $this->testUsers['user']['role'], $this->testUsers['user']['data'] );        
        $this->checkRedirect('Admin:User:get', '/admin/', ['id'=>1]);
        $this->checkRedirect('Admin:User:get', '/admin/', ['id'=>-1]);      
        $this->checkAction('Admin:User:get', ['id'=>$this->testUsers['user']['data']['id'] ]);
        $this->logOut();
        /** ============================================= */
		/** test admin with access */
		/** ============================================= */
        $this->login($this->testUsers['admin']['data']['id'], $this->testUsers['admin']['role'], $this->testUsers['admin']['data'] );        
        $this->checkRedirect('Admin:User:edit', '/admin/user/all/', ['id'=>-1]);        
        $this->checkAction('Admin:User:get', ['id'=>2]);
        $this->checkAction('Admin:User:get', ['id'=>$this->testUsers['user']['data']['id'] ]);

//        $dom = @\Tester\DomQuery::fromHtml($response->getSource()); // @ - not valid HTML
//        Assert::true( $dom->has('input[name="data"]') );
//        Assert::true( $dom->has('input[name="target"]') );
        $this->logOut();

    }


    public function testRenderUserEdit () {
        /** ============================================= */		
		/**  not login user  */
		/** ============================================= */
		$this->checkRedirect('Admin:User:edit', '/admin/sign/in/');
		$this->testUsers = $this->getPresenter()->context->parameters['testUsers'];
        
        /** ============================================= */
		/** test user no access */
        /** ============================================= */
        $this->login($this->testUsers['user']['data']['id'], $this->testUsers['user']['role'], $this->testUsers['user']['data'] );        
        $this->checkRedirect('Admin:User:edit', '/admin/', ['id'=>1]);
        $this->checkRedirect('Admin:User:edit', '/admin/', ['id'=>-1]);        
        $response = $this->checkAction('Admin:User:edit', ['id'=>$this->testUsers['user']['data']['id'] ]);
        $dom = @\Tester\DomQuery::fromHtml($response->getSource()); // @ - not valid HTML
        Assert::true( $dom->has('input[name="email"]') );
        Assert::false( $dom->has('input[name="status"]') );
        Assert::true( $dom->has('input[name="password"]') );
        $this->logOut();

        /** ============================================= */
		/** test admin with access */
		/** ============================================= */
        $this->login($this->testUsers['admin']['data']['id'], $this->testUsers['admin']['role'], $this->testUsers['admin']['data'] );        
        $this->checkRedirect('Admin:User:edit', '/admin/user/all/', ['id'=>-1]);        
        $this->checkAction('Admin:User:edit', ['id'=>2]);
        $response = $this->checkAction('Admin:User:edit', ['id'=>$this->testUsers['user']['data']['id'] ]);
        $dom = @\Tester\DomQuery::fromHtml($response->getSource()); // @ - not valid HTML
        Assert::true( $dom->has('input[name="email"]') );
        Assert::true( $dom->has('input[name="status"]') );
        Assert::true( $dom->has('input[name="password"]') );
        Assert::false( $dom->has('input[name="role_1"]') );
        Assert::true( $dom->has('input[name="role_2"]') );
        Assert::true( $dom->has('input[name="role_3"]') );
        Assert::true( $dom->has('input[name="role_4"]') );
        $this->logOut();

    }


    public function testRenderUserAdd () {
        /** ============================================= */		
		/**  not login user  */
		/** ============================================= */
		$this->checkRedirect('Admin:User:add', '/admin/sign/in/');
		$this->testUsers = $this->getPresenter()->context->parameters['testUsers'];
        
        /** ============================================= */
		/** test user no access */
        /** ============================================= */
        $this->login($this->testUsers['user']['data']['id'], $this->testUsers['user']['role'], $this->testUsers['user']['data'] );        
        $this->checkRedirect('Admin:User:add', '/admin/');

        /** ============================================= */
		/** test admin with access */
		/** ============================================= */
        $this->login($this->testUsers['admin']['data']['id'], $this->testUsers['admin']['role'], $this->testUsers['admin']['data'] );        
        $this->checkRedirect('Admin:User:edit', '/admin/user/all/', ['id'=>-1]);        
        $this->checkAction('Admin:User:edit', ['id'=>2]);
        $response = $this->checkAction('Admin:User:add', ['id'=>$this->testUsers['user']['data']['id'] ]);
        $dom = @\Tester\DomQuery::fromHtml($response->getSource()); // @ - not valid HTML
        Assert::true( $dom->has('input[name="email"]') );
        Assert::true( $dom->has('input[name="status"]') );
        Assert::true( $dom->has('input[name="password"]') );
        Assert::true( $dom->has('input[name="password_repeat"]') );
        Assert::true( $dom->has('input[name="status"]') );
        Assert::true( $dom->has('input[name="name"]') );
        Assert::true( $dom->has('input[name="username"]') );
        Assert::false( $dom->has('input[name="role_1"]') );
        Assert::true( $dom->has('input[name="role_2"]') );
        Assert::true( $dom->has('input[name="role_3"]') );
        Assert::true( $dom->has('input[name="role_4"]') );
        $this->logOut();

    }



}

(new AdminUserPresenterTest())->run();

