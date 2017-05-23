<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <title>Proyecto de Grado</title>
        <link rel="stylesheet" type="text/css" href="./sweetalert.css">
    </head>
    <body style="margin:0px;padding:0px">
        <div id="content"></div>        
		
		<script type="text/javascript" src="./kiwi.min.js"></script>
		<script src="./sweetalert.min.js"></script>
		<script>
		    //variables GLOBALES
			var state = new Kiwi.State('Play');
			
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
		    //hasta aqui!
		    
		
		    document.addEventListener("DOMContentLoaded", function(){
		        //funcion de cargue de ajax
				ajaxGet("http://pensante.nabu.com.co/core/BuscarRespuesta.php",comenzar,"http://pensante.nabu.com.co/");
			});
			
			function ajaxGet(url, funcion, url_servidor) {
			  var xhttp = new XMLHttpRequest();
			  xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					//console.log(this.responseText);
					json = this.responseText;
					instrucciones = JSON.parse(json);
					if(!instrucciones.hayRespuesta){
						location.reload(true);
					}else{
					    //la funcion es comenzar(json)
					    funcion(instrucciones);    
					}
					
				}else{
					
				}
			  };
			  xhttp.open("GET", url, true);
			  xhttp.send();
			}
			
			function comenzar(instrucciones){
			    
				mi_imagen = new Image();
				
				robot = new Image();
				
				mi_imagen.src = "http://pensante.nabu.com.co/Imagenes/Laberintos/"+instrucciones.imagen;
				
				robot.src= "../images/robot.png";
				
				//si no carga alguno reiniciamos hasta que cargue
				if(mi_imagen.height == 0){
				    location.reload(true);
				}
				 
				robot.height == 0 ? location.reload(true):console.log("cargo el robot");
				
				//obtenemos el tamaño de la imagen a partir de su 50%;
				mitad = mi_imagen.height/2;
				cuarto = mitad/2;
				//obtenemos el tamaño de la imagen del robot
				robot.mitad = ((robot.height*escalaRobot)-robot.height)/2;
				
				gameOptions.width=mi_imagen.height;
				gameOptions.height=mi_imagen.height;
				
				//comenzamo el juego
				game = new Kiwi.Game('', 'Proyecto de grado', state, gameOptions);
			}
		</script>
		<script>
		
			state.preload = function () {
				//cargamos el fondo
				
				this.addImage('tablero', mi_imagen.src);
				this.addImage('robot',robot.src);
			};

			state.create = function () {

			//variables
				//this.contador = 0;
				//objetos
				this.tablero = new Kiwi.GameObjects.Sprite(this, this.textures.tablero, 0, 0);
				
				this.robot = new Kiwi.GameObjects.Sprite(this, this.textures.robot,0,0);
				
				//Creamos el texto de fin de juego
				this.fin = new Kiwi.HUD.Widget.TextField( this.game, 'FIN DEL LABERINTO', 50, 50 );
				this.game.huds.defaultHUD.addWidget(this.fin);
				this.fin.x = -100;
				this.fin.y = -100;
				this.fin.style.color = '#5F5F5F';
				
				//creamos la altura en X y Y para ponerlos en 0 al iniciar
				this.robot.x2 = this.robot.y2 = 0;
				
				//console.log(robot.mitad);

				this.addChild(this.tablero);
				this.addChild(this.robot);
				
				//escalada del tablero
				//this.tablero.scaleY=0.5;
				//this.tablero.scaleX=0.5;
				//posicionamiento del tablero
				//this.tablero.x = -(cuarto);
				//this.tablero.y = -(cuarto);
				var escalada = escalaRobot;
				//escalada del robot
				this.robot.scaleY = this.robot.scaleX = escalada;
				//posicionamiento del robot
				tamanioDePantalla = (this.game.stage.width/8);
				this.robot.x = (robot.mitad);
				this.robot.y =(robot.mitad);
				//console.log(this.robot.x);
				
				//ahora calculamos el lugar de partida, apartir de estar en el 0,0
				if(instrucciones.puntoEntrada >= 0 && instrucciones.puntoEntrada <= 7){
					this.robot.x += instrucciones.puntoEntrada*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*0;
					//console.log("X: "+this.robot.x+" Y: "+this.robot.y);
					
				}else if(instrucciones.puntoEntrada >= 8 && instrucciones.puntoEntrada <= 15){
					this.robot.x += (instrucciones.puntoEntrada - (8*1))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*1;
					//console.log("X: "+this.robot.x+" Y: "+this.robot.y);
					
				}else if(instrucciones.puntoEntrada >= 16 && instrucciones.puntoEntrada <= 23){
					this.robot.x += (instrucciones.puntoEntrada - (8*2))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*2;
					//console.log("X: "+this.robot.x+" Y: "+this.robot.y);
					
				}else if(instrucciones.puntoEntrada >= 24 && instrucciones.puntoEntrada <= 31){
					this.robot.x += (instrucciones.puntoEntrada - (8*3))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*3;
					//console.log("X: "+this.robot.x+" Y: "+this.robot.y);
					
				}else if(instrucciones.puntoEntrada >= 32 && instrucciones.puntoEntrada <= 39){
					this.robot.x += (instrucciones.puntoEntrada - (8*4))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*4;
					//console.log("X: "+this.robot.x+" Y: "+this.robot.y);
					
				}else if(instrucciones.puntoEntrada >= 40 && instrucciones.puntoEntrada <= 47){
					this.robot.x += (instrucciones.puntoEntrada - (8*5))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*5;
					//console.log("X: "+this.robot.x+" Y: "+this.robot.y);
					
				}else if(instrucciones.puntoEntrada >= 48 && instrucciones.puntoEntrada <= 55){
					this.robot.x += (instrucciones.puntoEntrada - (8*6))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*6;
					//console.log("X: "+this.robot.x+" Y: "+this.robot.y);
					
				}else if(instrucciones.puntoEntrada >= 56 && instrucciones.puntoEntrada <= 63){
					this.robot.x += (instrucciones.puntoEntrada - (8*7))*tamanioDePantalla;
					this.robot.y += tamanioDePantalla*7;
					//console.log("X: "+this.robot.x+" Y: "+this.robot.y);
					
				}
				
				posicionAnterior.x = this.robot.x;
				posicionAnterior.y = this.robot.y;
				//this.robot.x = (robot.mitad)+672;
				//this.robot.y =(robot.mitad)+;//96 es el tamaño de cada cuadro
				
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
			
			state.update = function () {
				//se ejecutara toda la secuencia del robot andante
				//para despues realizar las rotaciones del robot para que se vea realiza
				//console.log(instrucciones.secuencia[indice]);
				
				//movimiento del robot va a ser de 5 px
				//console.log(instrucciones.secuencia[indice]);
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
				}
				
				
				if(indice == instrucciones.secuencia.length){
					//ejecutamos la secuencia de fin del juego
					//console.log("Fin del juego");
					//this.fin.style.fontSize = '40px';
					//this.fin.x = this.game.stage.width/4;
					//this.fin.y = this.game.stage.width/2;
					sweetAlert("Fin del Laberinto", "Para Cargar de nuevo el laberinto Actualize la pagina", "success");
					//this.game.states.switchState( "OutsideState" );
					//location.reload(true);
					//ajaxGet("http://pensante.nabu.com.co/core/BuscarRespuesta.php",comenzar,"http://pensante.nabu.com.co/");
					
				}
				
			};



		</script>
		
		<script>		
			/*
			Aqui se realiza las peticiones hacia el servidor donde debe responder que hay una solucion al laberinto
			*/
			var gameOptions = {
				renderer: Kiwi.RENDERER_WEBGL,
				width: 512,
				height: 512
			};
			
			//Las opciones del simulador
			
		
			
			
		</script>
	
    </body>
</html>