var app = angular.module('routing', ["ngRoute"]); 
app.controller('routingController', function ($scope, $http){
}); 


app.config(function($routeProvider) {
	$routeProvider
    .when("/", {
		templateUrl : "./pages/main.html"
    })
    .when("/page1", {
		templateUrl : "./pages/pagina1.html"
    })
    .when("/page2", {
        templateUrl : "./pages/pagina2.html"
    })
	.when("/page3", {
        templateUrl : "./listarDatos/listadoDatos.html",
		controller: "postresController"
    })
	.when("/page4", {
        templateUrl : "./formulario/formulario.html",
		controller: "formularioController"
    })
	.when("/page5", {
        templateUrl : "./tablas/tablas.html",
		controller: "tablasController"
    });
	
})

.config(function($httpProvider) {
	$httpProvider.interceptors.push('loadingStatusInterceptor');
})
 
.directive('loadingStatusMessage', function() {
	return {
		link: function($scope, $element, attrs) {
			var show = function() {
				$element.css('display', 'block');
			};
			var hide = function() {
				$element.css('display', 'none');
			};
			$scope.$on('loadingStatusActive', show);
			$scope.$on('loadingStatusInactive', hide);
			hide();
		}
	};
})
 
.factory('loadingStatusInterceptor', function($q, $rootScope) {
	var activeRequests = 0;
  
	var started = function() {
		if(activeRequests==0) {
			$rootScope.$broadcast('loadingStatusActive');
		}    
		activeRequests++;
	};
  
	var ended = function($timeout) {
		activeRequests--;
		if(activeRequests==0) {
			$rootScope.$broadcast('loadingStatusInactive');
		}
	};
  
	return {
		request: function(config) {
			started();
			return config || $q.when(config);
		},
		response: function(response) {
			ended();
			return response || $q.when(response);
		},
		responseError: function(rejection) {
			ended();
			return $q.reject(rejection);
		}
	};
});