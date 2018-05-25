<?php

namespace App\Components\Langswitch;

/**
 * Description of Langswitch
 *
 * @author Milan Machacek <machacek76@gmail.com>
 */



use Nette\Application\UI\Control;


class Langswitch extends Control {

	/** @var array links */
	public $links = array();

    
	private $templateFile = NULL;

	public function customTemplate($template){
		$this->templateFile = $template?$template:__DIR__ . '/Langswitch.latte';
	
	}

	
	/**
	 * Render function
	 */
	public function render()
	{
		$this->customTemplate($this->templateFile);
		
		$this->template->setFile($this->templateFile);

		$this->template->links = $this->links;
		$this->template->render();
	}

	/**
	 * Add link
	 *
	 * @param $title
	 * @param \Nette\Application\UI\Link $link
	 * @param null $icon
	 */
	public function addLink($title, $link = NULL, $icon = NULL)	{
		$this->links[md5($title)] = array(
			'title' => $title,
			'link'  => $link,
			'icon'  => $icon
		);
	}

	/**
	 * Remove link
	 *
	 * @param $key
	 *
	 * @throws Exception
	 */
	
	public function removeLink($key){
		$key = md5($key);
		if(array_key_exists($key, $this->links)){
			unset($this->links[$key]);
		} else{
			throw new Exception("Key does not exist.");
		}
	}
	
}
