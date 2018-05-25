<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;

use Nette\Utils\Html;

class FormFactory
{
	use Nette\SmartObject;


	/**
	 * @return Form
	 */
	public function create()
	{
		$form = new Form;
		return $form;
	}





}
