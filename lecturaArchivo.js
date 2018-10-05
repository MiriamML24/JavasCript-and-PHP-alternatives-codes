//Creación del módulo
var app = angular.module('uploadFile', []);

var array = Array();
var result = Array();
var texto = "";

//Creación del controlador 'uploadFile' con sus funciones
app.controller('uploadFile', function($scope, $http, $timeout) {
	
	$scope.flagShow = true;
	$scope.tableHead;
	$scope.columns;
	
	$scope.envio = function () {
		var file = $('#fichero').prop('files')[0];
		var reader = new FileReader();
		var name = file.name;
		name = name.split(".");
		
		if(name[1] == "csv") {
			$scope.flagShow = false;
			reader.onload = function() {
				texto = reader.result;
				/* console.log(texto); */
			}
			
			reader.readAsText(file);
			$timeout(function() {
				texto = texto.split(/\n/);
				for (i = 0; i < texto.length; i++){
					texto[i] = texto[i].split($scope.separador);
				}
				
				$scope.tableHead = texto[0];
				delete texto[0];
				$scope.rows = texto;
				/* console.log(texto);
				console.log(texto[0]); */
			}, 1000); 
		} 
		
		if(name[1] == "xlsx") {
			$scope.flagShow = false;
			reader.onload = function() {
				texto = reader.result;
				var workbook = XLSX.read(texto, { type: 'binary' });
				var sheet_name_list = workbook.SheetNames;
				sheet_name_list.forEach(function (y) {
					var roa = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
					if (roa.length > 0) {
						result[y] = roa;
					}
					console.log(result[y]);
				});
			
				for(var i in result[0]){
					array.push(i);
				}
				
				array = array.splice(0, array.length-1);
			}	
			reader.readAsArrayBuffer(file);
			
			$timeout(function(){
				$scope.tableHead = array;
				$scope.rows = result;
			}, 500);
		}
		
		if(name[1] == "xls") {
			$scope.flagShow = false;
			reader.onload = function() {
				texto = reader.result;
				var cfb = XLS.CFB.read(texto, {type: 'binary'});
				var wb = XLS.parse_xlscfb(cfb);
				wb.SheetNames.forEach(function(y) {
					var roa = XLS.utils.sheet_to_json(wb.Sheets[y]);
					if (roa.length > 0) {  
						result[y] = roa;
					}
					console.log(result);
				});
			}
			reader.readAsBinaryString(file);
			$timeout(function(){    
			$scope.columns = array;
			$scope.rows = result;
			},500);
		}
	}
});
