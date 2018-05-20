<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Front;


class HomepagePresenter extends BasePresenter{




	
	public function renderDefault(){
		
		$this->root['content']['title'] = 'Home Page';
		
		
	}
	
	
	
	


}
