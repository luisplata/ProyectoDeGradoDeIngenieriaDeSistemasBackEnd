<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <title>Proyecto de Grado</title>
        <link rel="stylesheet" type="text/css" href="./lib/sweetalert.css">
    </head>
    <body style="margin:0px;padding:0px">
        <div id="content"></div>        
		
		<script type="text/javascript" src="./lib/kiwi.js"></script>
		<script src="./lib/sweetalert.min.js"></script>
		
		<script>		
			/*
			Aqui se realiza las peticiones hacia el servidor donde debe responder que hay una solucion al laberinto
			*/
			
			document.addEventListener("DOMContentLoaded", function(){
				game = new Kiwi.Game('', 'Proyecto de grado', carga, gameOptions);
				//game.states.addState( carga );
				game.states.addState( lectura );
				game.states.addState( play );
				game.states.addState( fin );
			});
			//Las opciones del simulador
			
		</script>
		
		<script>
			
			
			var game;
			var mi_imagen;
			var mitad;
			var cuarto;
			var robot;
			var escalaRobot = 0.15;
			var json;
			var instrucciones ;
			var tamanioDePantalla;
			var posicionAnterior = {x:0,y:0};
			var gameOptions = {
				renderer: Kiwi.RENDERER_WEBGL,
				width: 615,
				height: 615
			};

			var carga = new Kiwi.State('carga');
			carga.preload = function(){
				//todo code...
				//solo cargo los datos para el cambio de estado
				console.log("Busqueda de recursos");
				document.title = "cargando!";
			}
			carga.create = function(){
				//todo code...
				//cambio de estado
				ajaxGet("http://localhost/proyecto_grado/core/BuscarRespuesta.php");
				game.states.switchState( "lectura" );
			}
			
			var lectura = new Kiwi.State('lectura');
			lectura.preload = function(){
				//todo code...
				console.log("Leyendo los datos");
				document.title = "Leyendo";
			}
			lectura.create = function(){
				//todo code...
				//cambio de estado
				//averiguamos si hay respuesta
				console.log(instrucciones);
				if(instrucciones.hayRespuesta == true){
					//leemos los datos
						
					mi_imagen = new Image();
					robot = new Image();
					
					mi_imagen.src = "http://localhost/proyecto_grado/Imagenes/Laberintos/"+instrucciones.imagen;
					robot.src= "./images/robot.png";
					
					//si no carga alguno reiniciamos hasta que cargue
					var error = false;
					mi_imagen.height == 0 ? error = true:console.log("cargo el laberinto");
					robot.height == 0 ? error = true:console.log("cargo el robot");
					
					//obtenemos el tamaño de la imagen del robot
					robot.mitad = ((robot.height*escalaRobot)-robot.height)/2;
					
					//cambio de estado
					if(error){
						game.states.switchState( "carga" );
					}else{
						game.states.switchState( "Play" );	
					}
				}else{
					game.states.switchState( "carga" );
				}	
			}
			
			var fin = new Kiwi.State('fin');
			fin.preload = function(){
				//todo code...
				console.log("fin del juego");
				document.title = "Fin del juego :'v";
			}
			fin.create = function(){
				//todo code...
				//cambio de estado
				
				swal({
				  title: "Fin del laberinto!",
				  text: "El robot ha llegado a su destino, desea cargar de nuevo el laberinto?",
				  type: "success",
				  showCancelButton: true,
				  confirmButtonColor: "#5c6bc0",
				  confirmButtonText: "Recargar",
				  cancelButtonColor: "#ef9a9a",
				  cancelButtonText: "Finalizar",
				  closeOnConfirm: true,
				  closeOnCancel: false
				},
				function(isConfirm){
				  if (isConfirm) {
					location.reload(true);
				  } else {
					swal("Gracias", "Por utilizar nuestro simulador", "success");
				  }
				});
			}
			
			var play = new Kiwi.State('Play');
			play.preload = function () {
				//cargamos el fondo
				document.title = "Jugando! :v";
				this.addImage('tablero', mi_imagen.src);
				this.addImage('robot',robot.src);
			};

			play.create = function () {

				//objetos
				this.tablero = new Kiwi.GameObjects.Sprite(this, this.textures.tablero, 0, 0);
				this.robot = new Kiwi.GameObjects.Sprite(this, this.textures.robot,0,0);
				
				//creamos la altura en X y Y para ponerlos en 0 al iniciar
				this.robot.x2 = this.robot.y2 = 0;
	
				//agregandolos a la escena
				this.addChild(this.tablero);
				this.addChild(this.robot);
				
				//escalada del robot
				this.robot.scaleY = this.robot.scaleX = escalaRobot;
				//posicionamiento del robot
				tamanioDePantalla = (this.game.stage.width/8);
				this.robot.x = (robot.mitad);
				this.robot.y =(robot.mitad);
				
				//ahora calculamos el lugar de partida, apartir de estar en el 0,0
				if(instrucciones.puntoEntrada >= 0 && instrucciones.puntoEntrada <= 7){
					this.robot.x += instrucciones.puntoEntrada*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*0;
					
				}else if(instrucciones.puntoEntrada >= 8 && instrucciones.puntoEntrada <= 15){
					this.robot.x += (instrucciones.puntoEntrada - (8*1))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*1;
					
				}else if(instrucciones.puntoEntrada >= 16 && instrucciones.puntoEntrada <= 23){
					this.robot.x += (instrucciones.puntoEntrada - (8*2))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*2;
					
				}else if(instrucciones.puntoEntrada >= 24 && instrucciones.puntoEntrada <= 31){
					this.robot.x += (instrucciones.puntoEntrada - (8*3))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*3;
					
				}else if(instrucciones.puntoEntrada >= 32 && instrucciones.puntoEntrada <= 39){
					this.robot.x += (instrucciones.puntoEntrada - (8*4))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*4;
					
				}else if(instrucciones.puntoEntrada >= 40 && instrucciones.puntoEntrada <= 47){
					this.robot.x += (instrucciones.puntoEntrada - (8*5))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*5;
					
				}else if(instrucciones.puntoEntrada >= 48 && instrucciones.puntoEntrada <= 55){
					this.robot.x += (instrucciones.puntoEntrada - (8*6))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*6;
					
				}else if(instrucciones.puntoEntrada >= 56 && instrucciones.puntoEntrada <= 63){
					this.robot.x += (instrucciones.puntoEntrada - (8*7))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*7;
					
				}
				
				posicionAnterior.x = this.robot.x;
				posicionAnterior.y = this.robot.y;
				
			};
			
			
			play.update = function () {
				//se ejecutara toda la secuencia del robot andante
				//para despues realizar las rotaciones del robot para que se vea realiza
				
				//se realiza el movimiento
				switch(instrucciones.secuencia[indice]){
					case 0:
						//adelante
						adelante(this.robot.x,this.robot.y,this.robot);
						//console.log(instrucciones.secuencia[indice]);
						break;
					
					case 2:
						//giro izquierda
						giroIzquierda(this.robot);
						//console.log(instrucciones.secuencia[indice]);
						break;
					
					case 1:
						//giro derecha
						giroDerecha(this.robot);
						//console.log(instrucciones.secuencia[indice]);
						break;
						
					//inclusion de nuevo movimiento 3 //atras
					case 3:
					    giroIzquierda(this.robot);
					    giroIzquierda(this.robot);
					    break;
				}
				
				
				if(indice == instrucciones.secuencia.length){
					//ejecutamos la secuencia de fin del juego
					game.states.switchState( "fin" );
				}
				
			};

			
			var indice = 0;//indicar por donde vamos
			var haciaDondeMiraElRobot = 2;//0=arriba;1=derecha;2=abajo;3=izquierda
			var velocidad = 1;
			
			function derecha(robot){
				robot.x += velocidad;
			}
			function izquierda(robot){
				robot.x -= velocidad;
			}
			function arriba(robot){
				robot.y -= velocidad;
			}
			function abajo(robot){
				robot.y += velocidad;
			}
			function adelante(x,y,robot){
				
				
				//0=arriba;1=derecha;2=abajo;3=izquierda
				switch (haciaDondeMiraElRobot){
					case 0:
						arriba(robot);
						console.log((posicionAnterior.y - tamanioDePantalla) +" = "+y);
						if((posicionAnterior.y - tamanioDePantalla) > y ){
							posicionAnterior.x = x;
							posicionAnterior.y = y;
							indice++;
						}
						break;
					case 1:
						derecha(robot);
						if((posicionAnterior.x + tamanioDePantalla) < x ){
							posicionAnterior.x = x;
							posicionAnterior.y = y;
							indice++;
						}
						break;
					case 2:
						abajo(robot);
						//console.log(y - tamanioDePantalla); 	
						if((posicionAnterior.y + tamanioDePantalla) < y ){
							posicionAnterior.x = x;
							posicionAnterior.y = y;
							indice++;
						}
						break;
					case 3:
						izquierda(robot);
						if((posicionAnterior.x - tamanioDePantalla) >= x ){
							posicionAnterior.x = x;
							posicionAnterior.y = y;
							indice++;
						}
						break;
				}
				
			}			
			function giroDerecha(robot){
				if(haciaDondeMiraElRobot == 3){
					haciaDondeMiraElRobot =0;
				}else{
					haciaDondeMiraElRobot++;
				}
				robot.rotation += Math.PI * 0.5
				indice++;
				console.log(haciaDondeMiraElRobot);
			}
			function giroIzquierda(robot){
				console.log(haciaDondeMiraElRobot);
				if(haciaDondeMiraElRobot == 0){
					haciaDondeMiraElRobot =3;
				}else{
					haciaDondeMiraElRobot--;
				}
				
				robot.rotation -= Math.PI * 0.5
				indice++;
			}		
			function ajaxGet(url) {
			  var xhttp = new XMLHttpRequest();
			  xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					console.log(this.responseText);
					json = this.responseText;
					instrucciones = JSON.parse(json);
					console.log(instrucciones);
				}
			  };
			  xhttp.open("GET", url, false);
			  xhttp.send();
			}
			

		</script>
		
	
    </body>
</html>