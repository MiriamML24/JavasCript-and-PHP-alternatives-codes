var app = angular.module("formulario", []);
var provincias = ['Álava','Albacete','Alicante','Almería','Asturias','Avila','Badajoz','Barcelona','Burgos','Cáceres', 'Cádiz','Cantabria','Castellón','Ciudad Real','Córdoba','La Coruña','Cuenca','Gerona','Granada','Guadalajara','Guipúzcoa','Huelva','Huesca','Islas Baleares','Jaén','León','Lérida','Lugo','Madrid','Málaga','Murcia','Navarra','Orense','Palencia','Las Palmas','Pontevedra','La Rioja','Salamanca','Segovia','Sevilla','Soria','Tarragona','Santa Cruz de Tenerife','Teruel','Toledo','Valencia','Valladolid','Vizcaya','Zamora','Zaragoza'];
var comunidades = ["Andalucía", "Aragón", "Canarias", "Cantabria", "Castilla y León", "Castilla-La Mancha", "Cataluña", "Ceuta", "Comunidad Valenciana", "Comunidad de Madrid", "Extremadura", "Galicia", "Islas Baleares", "La Rioja", "Melilla", "Navarra", "País Vasco", "Principado de Asturias", "Región de Murcia"];
var lenguajes = ["PHP", "Javascript", "SQL", "C#", "Java", "C++", "Phyton", "Rubi"];

app.controller('formularioController', function ($scope, $http){
	$scope.registro = function(nombre, apellido1, apellido2, email, terminos, sexo, provincias, comunidad, lenguajes, edad){
		console.log("Nombre: " + nombre + "\nPrimer apellido: " + apellido1 + "\nSegundo apellido: " + apellido2 + "\nEmail: " 
					+ email + "\nTerminos y condiciones: " + terminos + "\nSexo: " + sexo + " \nProvinicia: " + provincias 
					+ "\nComunidad: " + comunidad + "\nLenguaje de programación:" + lenguajes + "\nEdad: " + edad);
	}
});
					
function input(tipo, nombre, value){
	return " <input type='"+tipo+"' name='"+nombre+"' ng-model='"+nombre+"' value='"+value+"'/>";
}

function directiva(nombreDirectiva, html) {
	app.directive(nombreDirectiva, function() {
		return {
			template : html
		};
	});
}

function selectSimple(nombre, array){
	var cadena =  '<select id="'+nombre+'" ng-model="'+nombre+'" name="'+nombre+ '"class="selectpicker show-tick" data-live-search="true">';
	for (var i = 0; i < array.length; i++){
		cadena +=  '<option value ="'+array[i]+'">'+array[i]+'</option>';
	}
	cadena += '</select>';
	return cadena;
}

function selectMultiple(nombre, array, maxOptions){
	var cadena =  '<select id="'+nombre+'" ng-model="'+nombre+'" name="'+nombre+ '"class="selectpicker show-menu-arrow" multiple data-max-options="'+maxOptions+'">';
	for (var i = 0; i < array.length; i++){
		cadena +=  '<option value ="'+array[i]+'">'+array[i]+'</option>';
	}
	cadena += '</select>';
	return cadena;
}


directiva ("nombre", input('text','nombre', ''));
directiva ("apellido1", input('text','apellido1', ''));
directiva ("apellido2", input('text','apellido2', ''));
directiva ("radio1", input('radio','sexo', 'h'));
directiva ("radio2", input('radio','sexo', 'm'));
directiva ("edad", input('number', 'edad', ''));
directiva ("email", input('text', 'email', ''));
directiva ("terminos", input('checkbox','terminos', 'si'));
directiva ("file", input('file', 'file', ''));
directiva ("provincias", selectSimple('provincias', provincias));
directiva ("comunidades", selectSimple('comunidades', comunidades));
directiva ("lenguajes", selectMultiple('lenguajes', lenguajes, '5'));
directiva ("enviar", input('submit', 'enviar', 'Enviar'));