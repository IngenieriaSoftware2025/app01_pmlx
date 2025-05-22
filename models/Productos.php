<?php

namespace Model;

class Productos extends ActiveRecord{

    public $tabla ='productos';
    public $columnasDB = [
        'productos_nombre',
        'productos_cantidad',
        'categoria_id',
        'prioridad_id',
        'productos_comprado',
        'productos_fecha_compra',
        
    ];

    public $idTabla = 'productos_id';

    public $productos_id;
    public $productos_nombre;
    public $categoria_id;
    public $prioridad_id;
    public $productos_comprado;
    public $productos_fecha_compra;
   

public function __construct($argc =[]){
    $this->productos_id = $argc['productos_id']?? null;
    $this->productos_nombre = $argc['productos_nombre']?? '';
    $this->categoria_id = $argc['categoria_id']?? '';
    $this->prioridad_id = $argc['prioridad_id']?? '';
    $this->productos_comprado = $argc['productos_comprado']??'' ;
    $this->productos_fecha_compra = $argc['productos_fecha_compra']?? '';

}

 public static function EliminarProductos($id){

        $sql = "DELETE FROM productos WHERE productos_id = $id";

        return self::SQL($sql);
    }

}