// Creamos la variable 'app' y le damos el nombre del modulo
var app = angular.module('listarRegistros', []);
// Creamos el controllador 'postresController'
app.controller('postresController', function($scope, $http) {
	obtenerPersonal();
	$scope.insertar = function (nombre, apellido1, apellido2, email){
		$http.post("./ajax/insertarDatos.php?nombre="+nombre+"&apellido1="+apellido1+"&apellido2="+apellido2+"&email="+email).success(function(data){
			console.log(data);
			obtenerPersonal();
			$scope.nombre = "";
			$scope.apellido1 = "";
			$scope.apellido2 = "";
			$scope.email = "";
		});
	}
	
	/* $scope.editar = function (nombre, apellido1, apellido2, email){
		console.log("haces click");
	 	var isEditing = false;
		var parentRow = $(this).closest('tr');
		var tableBody = parentRow.closest('tbody');
		var tdNombre = parentRow.children('td.nombre');
		var tdApellido1 = parentRow.children('td.apellido1');
		var tdApellido2 = parentRow.children('td.apellido2');
		var tdEmail = parentRow.children('td.email');

		if (!isEditing) {
			var nombreNuevo = tableBody.find('input[name="nombre"]');
			var	apellido1Nuevo = tableBody.find('input[name="apellido1"]');
			var	apellido2Nuevo = tableBody.find('input[name="apellido2"]');
			var	emailNuevo = tableBody.find('input[name="email"]');
			var	tdNombreNuevo = nombreNuevo.closest('td');
			var	tdApellido1Nuevo = apellido1Nuevo.closest('td');
			var	tdApellido2Nuevo = apellido2Nuevo.closest('td');
			var	tdEmailNuevo = emailNuevo.closest('td');
			var	currentEdit = tdNombreNuevo.parent().find('td.edit');
			
			if ($(this).is(currentEdit)) {
				var tdNombreValor = nombreNuevo.prop('value');
				var	tdApellido1Valor = apellido1Nuevo.prop('value');
				var	tdApellido2Valor = apellido2Nuevo.prop('value');
				var	tdEmailValor = emailNuevo.prop('value');
				
				tdNombreNuevo.empty();
				tdApellido1Nuevo.empty();
				tdApellido2Nuevo.empty();
				tdEmailNuevo.empty();

				tdNombreNuevo.html(tdNombreValor);
				tdApellido1Nuevo.html(tdApellido1Valor);
				tdApellido2Nuevo.html(tdApellido2Valor);
				tdEmailNuevo.html(tdEmailValor);
				
			} else {
				tdNombreNuevo.empty();
				tdApellido1Nuevo.empty();
				tdApellido2Nuevo.empty();
				tdEmailNuevo.empty();
				
				tdNombreNuevo.html(nombre);
				tdApellido1Nuevo.html(apellido1);
				tdApellido2Nuevo.html(apellido2);
				tdEmailNuevo.html(email);
			}
			currentEdit.html('<i class="fa fa-pencil" aria-hidden="true"></i>');
			isEditing = false;
		} else {
			isEditing = true;
			$(this).html('<i class="fa fa-floppy-o" aria-hidden="true"></i>');
			var tdNombreValor = tdNombre.html();
			var tdApellido1Valor = tdApellido1.html();
			var tdApellido2Valor = tdApellido2.html();
			var tdEmailValor = tdEmail.html();
			
			nombre = tdNombreValor;
			apellido1 = tdApellido1Valor;
			apellido2 = tdApellido2Valor;
			email = tdEmailValor;
			
			nombre.empty();
			apellido1.empty();
			apellido2.empty();
			email.empty();
			
			nombre.html('<input type="text" name="name" value="' + tdNombreValor + '">');
			apellido1.html('<input type="text" name="data" value="' + tdApellido1Valor + '">');
			apellido2.html('<input type="text" name="data" value="' + tdApellido2Valor + '">');
			email.html('<input type="text" name="data" value="' + tdEmailValor + '">'); 
		}
	} */

	/*$('#validar').on('click', function(){
		var formulario = new FormData();
		formulario.append('nombre',$('#nombre').val());
		formulario.append('primerApellido', $('#apellido1').val());
		formulario.append('segundoApellido',$('#apellido2').val());
		formulario.append('email', $('#email').val());
			
		$.ajax({
			url: './ajax/insertarDatos.php', 
			type: 'POST',           
			data: formulario,   
			contentType: false,       
			cache: false,         
			processData:false, 								
			complete: function(response){
				console.log(response.responseText);
				obtenerPersonal();
			}  
		});	
	});
*/

	$scope.eliminar = function (nombre, apellido1, apellido2, email){
		$http.post("./ajax/eliminarDatos.php?nombre="+nombre+"&apellido1="+apellido1+"&apellido2="+apellido2+"&email="+email).success(function(data){
			console.log(data);
			obtenerPersonal();
		});
	}
	
	
	function obtenerPersonal(){
		$http.get("./ajax/buscarDatos.php").then(function (response) {
			$scope.names = response.data.records;
		});
	}
});

