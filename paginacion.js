/* Cargar el valor por defecto del selectpicker */
window.onload=function(){
	$('.selectpicker').selectpicker('val', 5);
}
/* Creamos el módulo y añadimos el array que va a contener la paginación */
var app = angular.module('paginacion', ['ui.bootstrap']);

/* Se crea el controlador del la aplicacón, y cuyas funciones van a ser de scope, http */
app.controller('paginacionController', function($scope, $http) {
	
	/* Saca el valor del html que se llama currentPage(pagina actual), totalItems (el número total de items), y pageSize (el tamaño de la página) */
	$scope.currentPage = 1;
    $scope.totalItems = 0;
    $scope.pageSize = 10;

   /* Se llama a las funciones de la cabecera que es la encargada de cargar los th en el html y de contenido que carga los td en el html */
	cabeceras();
	contenido();
	
	/* Cambia de pagina volviendo a cargar el contenido */
	$scope.pageChanged = function() {
        contenido();
    }
	
	/* Actualiza la página actual a una más y llama al contenido */
    $scope.pageSizeChanged = function() {
        $scope.currentPage = 1;
        contenido();
    }
	
	/* Es la función encargada de cargar los th del html, en esta función se llama a un php llamado cabecera del cual recibirá los datos de la cabecera y los pone en el html,
		este rellena la parte de columns. */
	function cabeceras() {
		$http.post("./ajax/cabecera.php").then(function(response){
			console.log(response.data);
			$scope.columns = response.data;
		}); 
	}
	
	/* Esta es la función encargada de cargar los td, llama a un php con los parámetros de la pagina actual y del tamaño de la pagina.
		Se establece el total de items, el item inicial y el final, y en él hay un bucle que rellena los assets con el contenido.*/
	function contenido(){
		$http.post("./ajax/contenido.php?page=" + $scope.currentPage + "&size=" + $scope.pageSize).then(function(response){
			console.log(response.data.result);
			console.log(response.data.totalCount);
			/* Estas son lo mismo uno lo crea en un array y el otro lo hace desde el php */
			//$scope.assets = response.data.result;
			$scope.assets = [];
			
			/* El número de datos totales  */
			$scope.totalItems = response.data.totalCount;
			/* La pagina actual */
			$scope.startItem = ($scope.currentPage - 1) * $scope.pageSize + 1;
			$scope.endItem = $scope.currentPage * $scope.pageSize;
			if ($scope.endItem > $scope.totalCount) {
				$scope.endItem = $scope.totalCount;
			}
			/* Carga el array creado arriba con los datos */
			angular.forEach(response.data.result, function(temp){
				$scope.assets.push(temp);
            });
		}); 
		
	}	
});