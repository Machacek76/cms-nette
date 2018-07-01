(function () {
	'use strict';



	angular
		.module('app')
		.controller('AppController', AppController);

	AppController.$inject = ['cmsApi', '$timeout'];

	function AppController(frnApi, $timeout) {
		
		var self						= this;
		self.debug						= true;
		
		self.targetTemplates			= __targetTemplates;
				
		////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////
		
	}

}());


