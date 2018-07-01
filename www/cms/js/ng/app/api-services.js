//(function () {
	'use strict';
	

	angular
		.module('app')
		.factory('cmsApi', frnApi);

	frnApi.$inject = ['$http'];

	function frnApi($http) {
		var service = {
			loadData		: loadData,
			sendData		: sendData,
			clearObject		: clearObject,
			log				: log,
			itemInArray		: itemInArray
		};

		return service;



		/**
		 * 
		 * @param {string} uri
		 * @returns {JSON}
		 */
		function loadData(uri, post) {
			
			if (typeof post !== 'undefined') {
				return $http.post(uri, {'ng': post})
					.then(getDataComplete)
					.catch(getDataFailed);
			}else{
				return $http.get(uri)
					.then(getDataComplete)
					.catch(getDataFailed);
			}
			
			function getDataComplete(response) {
				return response.data.data;
			}

			function getDataFailed(error) {
				console.log('XHR Failed for loadData.' + error.data);
			}
		}

		/**
		 * 
		 * @param {array} post
		 * @param {string} uri
		 * @returns {JSON}
		 */
		function sendData(post, uri) {
			return $http.post(uri, {'ng': post})
				.then(sendDataComplete)
				.catch(sendDataFailed);
			function sendDataComplete(response) {
				return response.data.data;
			}
			function sendDataFailed(error) {
				return 'XHR Failed for sendData.' + error.data;
			}
		}
		
		function clearObject(obj) {
			var bar;
			for (bar in obj) {
				if (typeof obj[bar] === 'object') {
					obj[bar] = clearObject(obj[bar]);
				} else {
					obj[bar] = '';
				}
			}
			return obj;
		}


		/**
		 * 
		 * @param {type} debug true / flase
		 * @param {type} log data to console log	
		 * @param {type} style 
		 * @returns {null}
		 */
		
		function log (debug, log, style){
			var st = style || '';
			if(debug === true){
				if (st === ''){
					console.log('api-services >> ', log)
				}else if (st === 'fn' ){
					console.log('%c app-controler >> ' + log, 'background:#B0E0E6; color:#000' )
				}else if (st === 'error'){
					console.log('%c app-controler >> ' + log, 'background:#f00; color:#000' )
				}else if (st === 'for'){
					console.log('%c app-controler >> ' + log, 'background:#EEE8AA; color:#000' )
				}
			}
		}
		
		
		
		function itemInArray (arr, item){
			var l = arr.length;
			for (var i = 0; i < l; i++ ){
				if (typeof item == 'object'){
					if (arr[i].id == item.id){
						return true;
					}
				}else{
					if (arr[i]['id'] == item['id']){
						return true;
					}
				}
			}
			return false;
		}
	

	}
	
//}());


