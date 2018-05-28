<?php //HomepagePresenterTest.phpt

require __DIR__ . '/bootstrap.php';


/**
 * @testCase
 */
class HomepagePresenterTest extends \Tester\TestCase{






	use \Testbench\TPresenter;




	public function testRenderDefault()	{
		$this->checkAction('Homepage:default');
	}



	public function testRenderFrontModule()	{
		$this->checkAction('Front:Homepage:default');
		
	}

    public function testRenderAdminModule (){
        $this->checkRedirect('Admin:Homepage:default', '/admin/sign/in/');
        $this->logIn(4, 'admin', ['email'=>'admin@example.com', 'id'=>4, 'name'=>'admin tester']);
		$this->checkAction('Admin:Homepage:default');
		$this->logOut();
    }


}

(new HomepagePresenterTest())->run();