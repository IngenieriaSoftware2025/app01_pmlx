<?php

namespace Model;

class Categorias extends ActiveRecord {

    public $tabla = 'categorias';
    public $columnasDB = [
        'nombre'
    ];

    public $idTabla = 'categoria_id';

    public $categoria_id;
    public $nombre;

    public function __construct($argc = []) {
        $this->categoria_id = $argc['categoria_id'] ?? null;
        $this->nombre = $argc['nombre'] ?? '';
    }

    public static function EliminarCategorias($id) {
        $sql = "DELETE FROM categorias WHERE categoria_id = $id";
        return self::SQL($sql);
    }
}