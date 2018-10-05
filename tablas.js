// Creamos la variable 'app' y le damos el nombre del modulo
var app = angular.module('buscarTabla', []);
var tipoOrdenacion = 0;
// Creamos el controllador 'postresController'
app.controller('tablasController', function($scope, $http) {
	obtenerPersonal();
	$scope.buscar = function (cadena){
		//console.log("dato introduccido: " + cadena);
		$http.post("./ajax/buscarDatos.php?cadena="+cadena).then(function (response) {
			console.log(response);
			$scope.names = response.data.records;
		});
	}
	
	$scope.ordenar = function (cadena, valor){
		ordenacion();
		$http.post("./ajax/ordenacion.php?cadena="+cadena+"&valor="+valor+"&tipoOrdenacion="+tipoOrdenacion).then(function (response) {
			console.log(response);
			$scope.names = response.data.records;
		});
	}
	
	function obtenerPersonal(){
		$http.get("./ajax/listaPersonal.php").then(function (response) {
			$scope.names = response.data.records;
		});
	}
	
	function ordenacion(){
		if (tipoOrdenacion == 0){
			tipoOrdenacion = 1;
		} else {
			tipoOrdenacion = 0;
		}
	}
});

