<?php
class Nodo 
{	
		var $id=0;
		var $ocupado = false;
		var $tipoNodo=0;
		var $color="";
		var $vertices=0;
        var $veticesLibres=0;
        var $nodoSiguiente=array();
        var $evaluado=false;
        var $pos_x=0;
        var $pos_y=0;
	function __construct($id, $ocupado, $tipoNodo, $color)
	{
		$this->id=$id;
        $this->ocupado = $ocupado;
        $this->tipoNodo=$tipoNodo;
        $this->color=$color;
	} 
	public function Set_id($id)
	{ 
		$this->id=$id;
	} 
        function getId() {
            return $this->id;
        }
        function getVeticesLibres() {
            return $this->veticesLibres;
        }

        function setVeticesLibres($veticesLibres) {
            $this->veticesLibres = $veticesLibres;
        }

                function getOcupado() {
            return $this->ocupado;
        }

        function getTipoNodo() {
            return $this->tipoNodo;
        }

        function getColor() {
            return $this->color;
        }

        function getVertices() {
            return $this->vertices;
        }

        function getNodoSiguiente() {
            return $this->nodoSiguiente;
        }

        function getEvaluado() {
            return $this->evaluado;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setOcupado($ocupado) {
            $this->ocupado = $ocupado;
        }

        function setTipoNodo($tipoNodo) {
            $this->tipoNodo = $tipoNodo;
        }

        function setColor($color) {
            $this->color = $color;
        }

        function setVertices($vertices) {
            $this->vertices = $vertices;
        }

        function setNodoSiguiente($nodoSiguiente) {
            $this->nodoSiguiente = $nodoSiguiente;
        }

        function setEvaluado($evaluado) {
            $this->evaluado = $evaluado;
        }

        	public function Get_color()
	{ 
		return $this->color; 
	} 
	public function Set_color($color)
	{ 
		$this->color=$color;
	} 

	public function Get_id()
	{ 
		return $this->id; 
	} 
	public function Set_ocupado($ocupado)
	{ 
		$this->ocupado=$ocupado;
	} 

	public function Get_ocupado()
	{ 
		return $this->ocupado; 
	} 

	public function Set_tipoNodo($tipoNodo)
	{ 
		$this->tipoNodo=$tipoNodo;
	} 
	
	public function Get_tipoNodo()
	{ 
		return $this->tipoNodo; 
	} 
	public function Get_vertices()
	{ 
		return $this->vertices; 
	}
       
        public function llenarVertices($nodos){
           $v1=$this->Get_nodoDerecho($nodos);
           if($v1!=null){
                          array_push($this->nodoSiguiente,intval($v1));
           }
            $v2=$this->Get_nodoAbajo($nodos);
           if($v2!=null){
                          array_push($this->nodoSiguiente,intval($v2));
           }
            $v3=$this->Get_nodoIzquierdo($nodos);
           if($v3!=null){
                          array_push($this->nodoSiguiente,intval($v3));
           }
            $v4=$this->Get_nodoArriba($nodos);
           if($v4!=null){
                          array_push($this->nodoSiguiente,intval($v4));
           }
            
        }
     
	public function Get_nodoArriba($nodos){
		$idNodoArriba =($this->id)-$nodos;
		if($idNodoArriba==0){
			$this->vertices=$this->vertices+1;
			return "0";
		} 
		if($idNodoArriba<0){
			return null;
		}
		$this->vertices=$this->vertices+1;
         
		return $idNodoArriba;
	}
	public function Get_nodoAbajo($nodos){
	$idNodoAbajo =($this->id)+$nodos;
		if($idNodoAbajo>=($nodos*$nodos)){
			return null;
		}
		$this->vertices=$this->vertices+1;
		
                return $idNodoAbajo;
	}
	public function Get_nodoDerecho($nodos){
		$idNodoDerecho =($this->id+1);
		if(($idNodoDerecho%$nodos)==0){
			return null;
		}
		if($idNodoDerecho>=($nodos*$nodos)){
			return null;
		}
		$this->vertices=$this->vertices+1;
		
		return $idNodoDerecho;
	}
	public function Get_nodoIzquierdo($nodos){
		if(($this->id%$nodos)==0){
			return null;
		}
		$idNodoIzquierdo =($this->id-1);
		if($idNodoIzquierdo==0){
			$this->vertices=$this->vertices+1;
			return "0";
		}
		if($idNodoIzquierdo<0){
			return null;
		}
		$this->vertices=$this->vertices+1;
                
		return $idNodoIzquierdo;
	}
} 
?>