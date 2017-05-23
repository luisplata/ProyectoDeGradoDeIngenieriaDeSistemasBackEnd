<?php
include_once("Nodo.php");
@session_start();
    /**
         * 
    * 
    */
    class Recorrido
    { 
            var $rutaFinal = array();
            var $respuesta = array();
            var $listaNodosBase = array();
            var $nodoActual;
            var $nodoInicial;
            var $puntoAnclaAnterior= array();
            var $caminoDeDirecciones= array();
            var $caminoDeIndicadores= array();
            var $posicion ="1"; 
            //var $posicion ="2"; izquierda
            //var $posicion="1"; abajo
            //var $posicion="0"; derecha

        function __construct($listaNodosBase)
        {       
                $this->listaNodosBase=$listaNodosBase;
                $this->asignarVerticesDisponibles();
        }
            public function Analizar()
            {

                  foreach ($this->listaNodosBase as $nodo) {   
                        if($nodo->tipoNodo == 1){
                            $this->nodoInicial=$nodo->getId();
                            $this->nodoActual=$nodo;
                            array_push($this->puntoAnclaAnterior,$nodo);
                            break;
                        }
                    }
                    if($this->nodoActual->getVeticesLibres()==0){
                        $this->solucionado=false;
                        $this->caminoDeIndicadores=null;
                        echo 6;
                        goto fin2;
                    }
                    array_push($this->rutaFinal,$this->nodoActual);

                    $contador=0;
                    $indiceEliminacion=0;
                    do{
                        
                        $nodoTemporal =array_shift($this->nodoActual->nodoSiguiente);
                        
                        @$nodoTemporal=$this->listaNodosBase[$nodoTemporal];
                        if($nodoTemporal==null || $nodoTemporal->ocupado || $nodoTemporal->evaluado){
                            
                     
                           

                            
                            if(count($this->nodoActual->nodoSiguiente)==0){
                                if (count($this->puntoAnclaAnterior)==0) {
                                        $this->solucionado=false;
                                        echo 5;
                                        goto fin2;

                                    }else{
                                        while (count($this->nodoActual->nodoSiguiente)==0) {
                                    
                                       $this->nodoActual->evaluado=true;
                                       $this->nodoActual=array_pop ($this->puntoAnclaAnterior);


                                    }                               
                                    
                                     
                                }


                            }
                            
                            $this->corregirRuta();

                        }else{
                                                                                                               
                            $this->nodoActual->evaluado=true;
                            $this->nodoActual=$nodoTemporal;
                             //var_export ($this->nodoActual);
                            if($this->nodoActual->getVeticesLibres()==1 && $this->nodoActual->tipoNodo!=3){
                                $this->nodoActual->evaluado=true;
                                $this->nodoActual=array_pop ($this->puntoAnclaAnterior);

                                
                                
                                $this->corregirRuta();


                            }else if($this->nodoActual->getVeticesLibres()>1 || $this->nodoActual->tipoNodo==3){
                                $this->nodoActual->evaluado=true;
                                if($this->nodoActual->getVeticesLibres()>2){
                                    array_push($this->puntoAnclaAnterior,$this->nodoActual);
                                }
                                
                                array_push($this->rutaFinal,$this->nodoActual);
                            }
                        }
                        
                        
                        $contador++;
                    }while($this->nodoActual->tipoNodo!=3);
                    for ($i=0; $i <10 ; $i++) { 
                        //$this->optimizarRuta();
                    }
                    //$this->optimizarRuta();

                    $this->solucionado=true;
                    echo 1;
                    fin:


                    
                    
                    $this->rutaConDirecciones();

                    fin2:
                    $this->respuesta=array("hayRespuesta"=>$this->solucionado, "imagen"=>($_SESSION['imagen']), "puntoEntrada"=>$this->nodoInicial,"secuencia"=>$this->caminoDeIndicadores);

                    $jsonencoded = json_encode($this->respuesta,JSON_UNESCAPED_UNICODE);
                    $fh = fopen("../Json/ResultadoAnalisis.json", 'w');
                    fwrite($fh, $jsonencoded);
                    date_default_timezone_set('America/Bogota');
                    $fechaActual=date('m_d_Y_H_i_s');
                    //$fechaActual=str_replace($fechaActual,"/", "_");
                    $fh = fopen("../Json/Historial/ResultadoAnalisis_".$fechaActual.".json", 'w');
                    fwrite($fh, $jsonencoded);
                    fclose($fh);

                    //echo "Resultado Generado: el json ha sido guardado";
                   // var_export($this->rutaFinal);
                    //$this->enderezarRuta();
            }
                public function optimizarRuta(){
                    //array_splice($this->rutaFinal,1,6);
                     $index_rutaFinal=0;
                     $index_rutaInterna=0;
                    foreach ($this->rutaFinal as $nodoFinal) {
                        $index_rutaFinal++;
                        foreach ( $this->rutaFinal as $nodoInterno) {
                            $index_rutaInterna++;
                                if(abs($nodoFinal->id-$nodoInterno->id)==8 && (abs($index_rutaInterna-$index_rutaFinal)-1)!=0){
                                  
                                  
                                    array_splice($this->rutaFinal,$index_rutaFinal,(abs($index_rutaInterna-$index_rutaFinal)-1));

                                    goto salirInterno;
                                }
                            
                        }
                        

                        $index_rutaInterna=0;
                        
                    }

                    salirInterno:

                }
                public function corregirRuta(){
                    $rutaArreglada = array();

                    foreach ($this->rutaFinal as $paso){
                        if($this->nodoActual->id==$paso->id){
                          array_push($rutaArreglada,$paso);
                          $this->rutaFinal=$rutaArreglada;
                         
                          break;

                        }else{
                          array_push($rutaArreglada,$paso);
                        }
                    }

                }
               /*public function enderezarRuta(){
                     var $array_retorno = array();
                    foreach($this->rutaFinal as $paso){
                            foreach($this->rutaFinal as $pasos){
                                    if (($paso->id+8)==($pasos->id)) {
                                        //array_splice($this->rutaFinal, 2);
                                        
                                    }

                            }

                    }


                }*/
                public function caminoDeNodos(){

                    
                    foreach ($this->rutaFinal as $paso){
                        echo $paso->id."->";
                   
                    }
                    echo "fin";

                }
                public function rutaConDirecciones()
                {
                    $nodoAnterior=$this->rutaFinal[0];
                    
                   foreach ($this->rutaFinal as $paso){
                        if($nodoAnterior!=null){
                                

                            switch ($this->posicion) {
                                case '0':
                                    if($paso->id==(($nodoAnterior->id)+8)){
                                        $this->posicion="1";
                                        array_push($this->caminoDeIndicadores,1);
                                        array_push($this->caminoDeIndicadores,0);

                                        array_push($this->caminoDeDirecciones,"Giro a la derecha y Avanza");

                                    }
                                     if($paso->id==($nodoAnterior->id-8)){
                                        $this->posicion="3";

                                        array_push($this->caminoDeIndicadores,2);
                                        array_push($this->caminoDeIndicadores,0);

                                        array_push($this->caminoDeDirecciones,"Giro a la izquierda y Avanza");

                                    }
                                     if($paso->id==($nodoAnterior->id+1)){
                                        array_push($this->caminoDeIndicadores,0);
                                        array_push($this->caminoDeDirecciones,"Avanza");

                                      
                                      $this->posicion="0";

                                    }
                                    break;
                                case '1':
                                       if($paso->id==($nodoAnterior->id+8)){
                                        array_push($this->caminoDeIndicadores,0);
                                        array_push($this->caminoDeDirecciones,"Avanza");
                                      $this->posicion="1";

                                    }
                                     if($paso->id==($nodoAnterior->id-1)){
                                        array_push($this->caminoDeIndicadores,1);
                                        array_push($this->caminoDeIndicadores,0);

                                        array_push($this->caminoDeDirecciones,"Giro a la derecha y Avanza"); 
                                      $this->posicion="2";

                                    }
                                     if($paso->id==($nodoAnterior->id+1)){
                                        array_push($this->caminoDeIndicadores,2);
                                        array_push($this->caminoDeIndicadores,0);

                                        array_push($this->caminoDeDirecciones,"Giro a la izquierda y Avanza");
                                        $this->posicion="0";
                                    }
                                    break;
                                case '2':
                                    if($paso->id==($nodoAnterior->id+8)){
                                        array_push($this->caminoDeIndicadores,2);
                                        array_push($this->caminoDeIndicadores,0);

                                        array_push($this->caminoDeDirecciones,"Giro a la izquierda y Avanza"); 
                                      $this->posicion="1";

                                    }
                                     if($paso->id==($nodoAnterior->id-1)){
                                        array_push($this->caminoDeIndicadores,0);
                                        array_push($this->caminoDeDirecciones,"Avanza");                                     
                                        $this->posicion="2";

                                    }
                                     if($paso->id==($nodoAnterior->id-8)){
                                        array_push($this->caminoDeIndicadores,1);
                                        array_push($this->caminoDeIndicadores,0);
                                        array_push($this->caminoDeDirecciones,"Giro a la derecha y Avanza"); 
                                      $this->posicion="3";

                                    }
                                    break;
                                case '3':
                                    if($paso->id==($nodoAnterior->id-8)){
                                        array_push($this->caminoDeIndicadores,0);
                                        array_push($this->caminoDeDirecciones,"Avanza");                                   
                                      $this->posicion="3";

                                    }
                                     if($paso->id==($nodoAnterior->id-1)){
                                        array_push($this->caminoDeIndicadores,2);
                                        array_push($this->caminoDeIndicadores,0);
                                        array_push($this->caminoDeDirecciones,"Giro a la izquierda y Avanza");                                     
                                      $this->posicion="2";

                                    }
                                     if($paso->id==($nodoAnterior->id+1)){
                                        array_push($this->caminoDeIndicadores,1);
                                        array_push($this->caminoDeIndicadores,0);

                                        array_push($this->caminoDeDirecciones,"Giro a la derecha y Avanza");                                     
                                      $this->posicion="0";

                                    }                                
                                    break;
                            
                            }
                                 


                        }
                       
                    $nodoAnterior=$paso;

                    }
                    
                     

                }
                public function asignarVerticesDisponibles()
                {
                    foreach ($this->listaNodosBase as $nodo) {  
                       $verticesLibres=0;
                        
                        foreach ($nodo->getNodoSiguiente() as $nodoSiguiente) {  
                            if($this->listaNodosBase[$nodoSiguiente]->ocupado==false){
                                $verticesLibres=$verticesLibres+1;
                            }
                        }
                        $nodo->setVeticesLibres($verticesLibres);
                    }
                }
                function getRutaFinal() {
                    return $this->rutaFinal;
                }

                function getListaNodosBase() {
                    return $this->listaNodosBase;
                }

                function getNodoActual() {
                    return $this->nodoActual;
                }

                function setRutaFinal($rutaFinal) {
                    $this->rutaFinal = $rutaFinal;
                }

                function setListaNodosBase($listaNodosBase) {
                    $this->listaNodosBase = $listaNodosBase;
                }

           
                function getPuntoAnclaAnterior() {
                    return $this->puntoAnclaAnterior;
                }

                function setPuntoAnclaAnterior($puntoAnclaAnterior) {
                    $this->puntoAnclaAnterior = $puntoAnclaAnterior;
                }

                function setNodoActual($nodoActual) {
                    $this->nodoActual = $nodoActual;
                }

                        public function Get_listaNodosBase()
        { 
            return $this->listaNodosBase; 
        } 
        public function Set_listaNodosBase($listaNodosBase)
        { 
            $this->listaNodosBase=$listaNodosBase;
        }
        public function agregarNodoRutaFinal( $nuevoElemento)
        { 
            array_push($this->rutaFinal, $nuevoElemento);

        }
        public function agregarNodoListaNodosTemporal( $nuevoElemento)
        { 
            array_push($this->listaNodosTemporal, $nuevoElemento);

        }
              
        
        
         

    }
?>