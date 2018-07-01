
(function () {
	'use strict';


	app.directive ('contenteditable', contenteditable);
	app.directive ('imgPreview', imgPreview);
	app.directive ('datetimepicker', datetimepicker);


	function datetimepicker (){
		return {
			require: '?ngModel',
			restrict: 'A',
			link: function(scope, element, attrs, ngModel){
				if(!ngModel) return; // do nothing if no ng-model
				element.on('dp.change', function(){
					scope.$apply(read);
				});
				read();
				function read() {
					var value = element.find('input').val();
					if(value !== ''){
						ngModel.$setViewValue(value);
					}
				}
			}
		}
	}
	

	
	function contenteditable () {
		return {
			restrict: "AE",
			require: "ngModel",
			link: function(scope, element, attrs, ngModel) {

				function read() {
					var str = element.html();
					str = str.replace(/<br.[^>]*?>/g, "\n");	// zalomeni
//					str = str.replace(/<[^>]*?>/g, "");			// odstraneni html
//					str = str.replace(/&nbsp;/g, " ");			// odstraneni html
//					str = str.replace(/(&[a-zA-Z]{1,8};)/g, "");			// odstraneni html
					ngModel.$setViewValue(str);
				}

				ngModel.$render = function() {
					element.html(ngModel.$viewValue || "");
				};

				element.bind("blur keyup change", function() {
					scope.$apply(read);
				});
			}
		};
	}




	function imgPreview() {
		var directive = {
			restric: 'E',
			templateUrl: '/admin-main/ng/app/templates/imgPreview.html?v=1',
			scope: {
				'detail'			: '=of',
				'index'				: '=',
				'higlightClass'		: '@',
				'remove'			: '=remove',
				'editable'			: '=editable'
			}
		}
		return directive;
	}
	
})()
