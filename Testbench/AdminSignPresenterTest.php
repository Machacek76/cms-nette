<?php


require __DIR__ . '/bootstrap.php';

use Tester\Assert;

class AdminSignPresenterTest  extends \Tester\TestCase {
    

	use \Testbench\TPresenter;



    public function testRenderSignIn (){

        $response = $this->checkAction('Admin:Sign:in', [], ['password'=>'55486321', 'username'=>'fakeUser'] );
        $dom = @\Tester\DomQuery::fromHtml($response->getSource()); // @ - not valid HTML
        Assert::true( $dom->has('input[name="username"]') );
        Assert::true( $dom->has('input[name="password"]') );

        $this->checkForm('Admin:Sign:in', 'signInForm', ['password'=>'55486321', 'username'=>'testuser'], '/admin/');

    }




}

(new AdminSignPresenterTest())->run();

