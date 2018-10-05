window.onload=function(){
	$('.selectpicker').selectpicker('val', 'default');
}

var app = angular.module('formulario', ["ngRoute"]); 
var flag = false;

app.controller('formularioController', function ($scope, $http, $timeout){
	//$scope.arrayComunidades =   [{'nombre' : 'Cargando...'}];
	//$scope.arrayProvincias =  [{'nombre' : 'Cargando...'}];
	$scope.arrayLenguajes = ["PHP", "Javascript", "SQL", "C#", "Java", "C++", "Phyton", "Rubi"];
	
	$scope.terminos = {
		valor : false
	};
	
	$scope.registro = function(nombre, apellido1, apellido2, email, terminos, sexo, provincias, comunidad, lenguajes, edad){
		console.log("Nombre: " + nombre + "\nPrimer apellido: " + apellido1 + "\nSegundo apellido: " + apellido2 + "\nEmail: " 
					+ email + "\nSexo: " + sexo + " \nProvinicia: " + provincias + "\nComunidad: " 
					+ comunidad + "\nLenguaje de programación:" + lenguajes + "\nEdad: " + edad
					+ "\nTerminos y condiciones: " + terminos.valor);
	}
	
	$scope.obtenerComunidades = function(){
		if (flag != true){
			var flag = true;
			$http.post("./ajax/selectComunidades.php").then(function(response){
				$('#default').remove();
				$('#cargando').remove();
				console.log(response.data);
				$scope.arrayComunidades = response.data;
				console.log($scope.arrayComunidades);
			});
		}
		$timeout(function(){
			$('.selectpicker').selectpicker('refresh');			
		},250)
	}
	
	$scope.obtenerProvincias = function(){
		if (flag != true){
			var flag = true;
			$http.post("./ajax/selectProvincias.php").then(function(response){
				$('#default').remove();
				$('#cargando').remove();
				console.log(response.data);
				$scope.arrayProvincias = response.data;
				console.log($scope.arrayProvincias);
			});
		}
		$timeout(function(){
			$('#select').selectpicker('refresh');			
		},250)
	}
	
});

function directiva(nombreDirectiva, url) {
	app.directive(nombreDirectiva, function() {
		return {
			templateUrl : url
		};
	});
}

directiva("nombre", "./templates/inputNombre.html");
directiva("apellido1", "./templates/inputApellido1.html");
directiva("apellido2", "./templates/inputApellido2.html");
directiva("edad", "./templates/inputEdad.html");
directiva("sexo", "./templates/radioSexo.html");
directiva("email", "./templates/inputEmail.html");
directiva("file", "./templates/inputFile.html");
directiva("comunidades", "./templates/selectSimple.html");
directiva("provincias", "./templates/selectSimple2.html");
directiva("lenguajes", "./templates/selectMultiple.html");
directiva("terminos", "./templates/inputCheckbox.html");

