<?php

namespace Model;

class Prioridades extends ActiveRecord {

    public $tabla = 'prioridades';
    public $columnasDB = [
        'nivel'
    ];

    public $idTabla = 'prioridad_id';

    public $prioridad_id;
    public $nivel;

    public function __construct($argc = []) {
        $this->prioridad_id = $argc['prioridad_id'] ?? null;
        $this->nivel = $argc['nivel'] ?? '';
    }

    public static function EliminarPrioridades($id) {
        $sql = "DELETE FROM prioridades WHERE prioridad_id = $id";
        return self::SQL($sql);
    }
}