//(function () {
//'use strict';


var app = angular.module('app', []);

/// FILTER

		angular.module('app')
		.filter('isEmpty', function () {
			var bar;
			return function (obj) {
				for (bar in obj) {
					if (obj.hasOwnProperty(bar)) {
						return false;
					}
				}
				return true;
			};
		});

		angular.module('app')
		.filter('html', function ($sce) {
			return function (val) {
				return $sce.trustAsHtml(val);
			};
		});




// }());