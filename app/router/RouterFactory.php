<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;
		
		
		$router[] = new Route('admin/[<locale=cs cs|en>/]<presenter>/<action>/<id>', array(
		    'module'    => 'Admin',
		    'presenter' => 'Homepage',
		    'action'    => 'default',
		    'id'        => NULL,
		));
        
        $router[] = new Route('api/<presenter>/<action>/<id>/<uid>[/<uuid>]', array(
		    'module'    => 'Api',
		    'presenter' => 'Default',
		    'action'    => 'default',
		    'id'        => NULL,
            'uid'       => NULL,
			'uuid'		=> NULL,
		)); 

        $router[] = new Route('<name>/<id>[/<uid>]', array(
            'module'    => 'Front',
            'presenter' => 'Default',
            'action'    => 'default',
            'id'        => NULL,
			'uid'       => NULL,
        ));


		$router[] = new Route('[<locale=cs cs|en>/]<presenter>/<action>/<id>/<uid>[.html]', array(
		    'module'    => 'Front',
		    'presenter' => 'Homepage',
		    'action'    => 'default',
		    'id'        => 'home',
		    'uid'       => '',
		));
		
		
		$router[] = new Route('image/load/<id>/<width>/<height>/<type>/<ext>', array(
		    'module' => 'Front',
		    'presenter' => 'Image',
		    'action' => 'load',
		    'id' => '',
		    'width' => '',
		    'height' => '',
		    'type' => '',
		    'ext' => '',
		));


		
		return $router;
	}
}
