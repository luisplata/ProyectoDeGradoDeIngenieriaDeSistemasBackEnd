<?php
@session_start();
include_once("colors.inc.php");
include_once("Nodo.php");
include_once("Recorrido.php");



switch ($_GET['r']) {
	case 1:
		crear_imagen();
	break;
	case 2:
	    core();
	break;
	
	default:
		echo "error";
	break;
}

function crear_imagen(){
$img = $_POST['img'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$im = imagecreatefromstring($data);  //convertir text a imagen
if ($im !== false) {
	date_default_timezone_set('America/Bogota');
    $fechaActual=date('m_d_Y_H_i_s');
    $_SESSION["imagen"]="Tablero".$fechaActual.".jpg";
    imagejpeg($im, '../Imagenes/Laberintos/Tablero'.$fechaActual.'.jpg'); //guardar a server 
    imagedestroy($im); //liberar memoria  
    echo 'Todo ha salido bien tu Laberintos está siendo analizado.';
}else {
    echo 'Un error ocurrio al convertir la imagen.';    
}

}

function generar_imagen($inicio,$alto,$partes,$imagen, $i_y)
{
	$inicio_x  = $inicio;
	$inicio_y  = $i_y;
	$ancho     = $partes;
	$alto      = $alto;
	$termina_x = 0;
	$termina_y = 0;
	$imagen = imagecreatefromjpeg($imagen);
	$dst_im = imagecreatetruecolor($ancho, $alto);
	$white = imagecolorallocate($dst_im, 255, 255, 255);
	imagefill($dst_im, 0, 0, $white);
 
	imagecopy($dst_im, $imagen, $termina_x, $termina_y, $inicio_x, $inicio_y, $ancho, $alto);
	return $dst_im; 	
}
function hex2rgb($hex) {
   //$hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
	//Alteración del método:
   /*
		el método retornará un numero indicando si el color es más rojo, azul o verde
   */
	if($g>$r && $g>$b){
			return 1;
			//1: indicará verde(inicial)
	}

	if($r>$g && $r>$b){
			return 2;
			//2: indicará rojo(final)
	}
}
 function Core()
 {
$ex=new GetMostCommonColors();
$cantidadDeNodos=8;
$imagen      = '../Imagenes/Laberintos/'.$_SESSION["imagen"];
/*$imagen      = '../Imagenes/Laberintos/'.$_SESSION["imagen"].'.jpg';
do{
	$existeArchivo = file_exists($imagen);
}while ($existeArchivo==false);
*/


			//$imagen      = 't.jpg';
			$imagen1     = getimagesize($imagen); 
			$ancho       = $imagen1[0];
			$alto        = $imagen1[1]/$cantidadDeNodos;
			$nuevo_ancho = floor($ancho / $cantidadDeNodos);
			$inicial = 0;
			$inicio_y=0;
			//Variables para la identificacion de colores
			$delta = 24;
			$reduce_brightness = true;
			$reduce_gradients = true;
			$num_results = 1;
			$listaNodos = array();
			$listaNodosDisponibles = array();
			$elementos = array();

			$indice =0;


			for($a = 0; $a <$cantidadDeNodos; $a++):
			$inicial = 0;
				
				for($i = 0; $i < $cantidadDeNodos; $i++):
					$imagen_ = generar_imagen($nuevo_ancho*$inicial,$alto,$nuevo_ancho,$imagen,($alto*($a)));
					imagejpeg($imagen_,'../Imagenes/Fragmentos/Parte_'.($a).'_'.$i.'.jpg',95);
					$colors=$ex->Get_Color('../Imagenes/Fragmentos/Parte_'.($a).'_'.$i.'.jpg', $num_results, $reduce_brightness, $reduce_gradients, $delta);

						//echo "<img src='Imagenes/Parte_".($a-1)."_".$i.".jpg' alt='test image' width='100px'  height='100px' />";
						

						foreach ( $colors as $hex => $count )
						{
							if ( $count > 0 )
							{
								//echo "</br><tr><td style=\"background-color:#".$hex.";\"></td><td>".$hex."</td>  P:<td>".($count*100)."%"."</td></tr>";
								 $ocupado=false;
								if($hex=="000000" && ($count*100)>70){
									$ocupado=true;
									$Nodo=new Nodo($indice, $ocupado, 2, $hex);
									$elementos[$indice] =$Nodo;

								}else{
									$Nodo=new Nodo($indice, $ocupado, 2,$hex);
									$elementos[$indice] =$Nodo;	
									array_push($listaNodosDisponibles, $Nodo);

								}
								//echo "[inicial] color a comparar (".$colorNodoInicial.") vs ".$hex."<br/>";
								 if(hex2rgb($hex)==1){
									$Nodo=new Nodo($indice, $ocupado, 1,$hex);
									$elementos[$indice] =$Nodo;
								}
								//echo "[final] color a comparar (".$colorNodoFinal.") vs ".$hex;
								 if(hex2rgb($hex)==2){
									$Nodo=new Nodo($indice, $ocupado, 3,$hex);
									$elementos[$indice] =$Nodo;
								}

							}


						}





					$inicial++;
					$indice++;
				endfor;
			endfor;
			//var_export ($elementos);
			// Estableciendo los nodos proximos
			  		foreach ($elementos as $elemento) {
			            $elemento->llenarVertices($cantidadDeNodos);
			       }
			  	
			      // Recorrido
				  //var_export($elementos);
			      $recorrido=new Recorrido($elementos);
		      	  $recorrido->analizar();
		      	 
			       // listaNodosDisponibles = nodos que no están ocupados.
 }
?>
