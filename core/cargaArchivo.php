<?php
$status = "";
// obtenemos los datos del archivo
$tamano = $_FILES["archivo"]['size'];
$tipo = $_FILES["archivo"]['type'];
$archivo = $_FILES["archivo"]['name'];
$prefijo = substr(md5(uniqid(rand())),0,6);
if ($archivo != "") {
// guardamos el archivo a la carpeta files
$destino = "Imagenes/Laberintos/Tablero13.jpg";
if (copy($_FILES['archivo']['tmp_name'],$destino)) {
$status = "Archivo subido: ".$archivo."";
} else {
$status = "Error al subir el archivo";
}
} else {
$status = "Error al subir archivo";
}

echo $status;









