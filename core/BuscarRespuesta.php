<?php 
header('Access-Control-Allow-Origin: *');  
 if(file_exists ("../Json/ResultadoAnalisis.json")){

 	$str_datos = file_get_contents("../Json/ResultadoAnalisis.json");
 	echo $str_datos;
 }else{

 	echo "no existe aun una respuesta";
 }
 ?>