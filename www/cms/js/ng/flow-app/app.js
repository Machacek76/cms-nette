(function () {


	/*global angular */
	'use strict';

	/**
	 * The main app module
	 * @name app
	 * @type {angular.Module}
	 */
	var app = angular.module('app', ['flow'])
		.config(
			['flowFactoryProvider', function (flowFactoryProvider) {
					flowFactoryProvider.defaults = {
						target: '/api/image-flow/upload',
						permanentErrors: [404, 500, 501],
						maxChunkRetries: 1,
						chunkRetryInterval: 5000,
						simultaneousUploads: 4
					};
					flowFactoryProvider.on('catchAll', function (event) {
						console.log('catchAll', arguments);
					});

					// Can be used with different implementations of Flow.js
					// flowFactoryProvider.factory = fustyFlowFactory;
				}])
	app.filter('debug', function () {
		return function (input) {
			if (input === '')
				return 'empty string';
			return input ? input : ('' + input);
		};
	});
	
	app.filter('myFloor', function (){
		return function (num){
			return Math.floor(num*1000)/10;
		}
	})
	
	
}())

