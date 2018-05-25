<?php

namespace App\Presenters;

use Nette;
use App\Model;
use App\Components\Breadcrumb;
use App\Components\Langswitch;
use IPub\VisualPaginator\Components as VisualPaginator;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	
	
	
	protected $visualPaginatorFactory;
	
	
	
	public  $root = array();

	
	/** @persistent */
    public $locale;

    /** @var \Kdyby\Translation\Translator @inject */
    public $translator;
	
	/** @var \GlueWork\glCache\glCacheExtension @ inject */
	public $glCache;
	
	public $httpRequest;
	
	public $httpResponse;
	
		


	
	/**
	 * breadcrumb
	 */
	protected function createComponentBreadCrumb(){
		$breadCrumb = new \App\Components\Breadcrumb\Breadcrumb();
		$breadCrumb->addLink($this->translator->trans('ui.breadcrumb.home'), $this->link('Homepage:'), 'fa fa-home');
		return $breadCrumb;
	}
	
	
	public function startup() {
		parent::startup();

		
		
		$this->httpRequest = $this->context->getByType('Nette\Http\Request');
		$this->httpResponse = $this->context->getByType('Nette\Http\Response');
		
		// insert GET params
        $this->root['system']['request']		= $this->httpRequest->getQuery();
        
        // insert cache params
        // TODO to settings from cms
		$this->root['system']['jsVersion']		= time();
		$this->root['system']['cssVersion']		= time();
        
        // setting GL cache
		$this->glCache = $this->context->getService('glCache');
		$this->glCache->initCache($this->context->parameters['glCache']);
		$this->glCache->nocache = $this->httpRequest->getQuery('nocache') === NULL ? false : true;
	}
	
	
	/*********************************************
	 * 
	 * 	REGISTERS HELPERS
	 * 
	 *********************************************/

	protected function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);
		$self = $this;


		/**
		 * IMAGES
		 */
		$template->addFilter('getImage', function ($data) use ($self) {
			$filename = '/' . $self->context->parameters['imageTemp'] . '/' . $data['id'] . '-' . $data['width'] . '-' . $data['height'] . '-' . $data['type'] . '.' . $data['ext'];
			if (file_exists($self->context->parameters['wwwDir'] . $filename) == false) {
				$filename = '/image/load/' . $data['id'] . '/' . $data['width'] . '/' . $data['height'] . '/' . $data['type'] . '/' . $data['ext'];
			}
			return $filename;
		});

		
		return $template;
	}
	
	
	/**
	 * switchlang
	 */
	protected function createComponentLangswitch (){
		$langswitch = new \App\Components\Langswitch\Langswitch();
		$langswitch->addLink('CS', $this->link('this', ['locale'=>'cs']), 'flag-icon flag-icon-cz');
		$langswitch->addLink('EN', $this->link('this', ['locale'=>'en']), 'flag-icon flag-icon-us');
		return $langswitch;
	}
	

	/**
	 * PAGINATOR
	 * @return \IPub\VisualPaginator\Components\Control
	 * 
	 *  In presenter
	 *  $visualPaginator = $this['vp'];
     *  $paginator = $visualPaginator->getPaginator();
     *  $paginator->itemsPerPage = 15;
     *  $paginator->itemCount = 185;
	 * 
	 *  In templates
	 *  {control vp}
	 */
	protected function createComponentVp() {
        $control = new VisualPaginator\Control;
        $control->setTemplateFile('bootstrap.latte');
        $control->disableAjax();
		$control->setTemplateFile($this->context->parameters['appDir'].'/Components/paginator/VisualPaginator.latte');

        return $control; 
    }




    
}
