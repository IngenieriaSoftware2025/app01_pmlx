<?php

namespace Model;

class Productos extends ActiveRecord {

    public static $tabla = 'productos';
    public static $columnasDB = [
        'producto_nombre',
        'producto_cantidad', 
        'producto_categoria',
        'producto_prioridad',
        'producto_comprado',
        'producto_situacion'
    ];

    public static $idTabla = 'producto_id';
    
    public $producto_id;
    public $producto_nombre;
    public $producto_cantidad;
    public $producto_categoria;
    public $producto_prioridad;
    public $producto_comprado;
    public $producto_situacion;

    public function __construct($args = []){
        $this->producto_id = $args['producto_id'] ?? null;
        $this->producto_nombre = $args['producto_nombre'] ?? '';
        $this->producto_cantidad = $args['producto_cantidad'] ?? 1;
        $this->producto_categoria = $args['producto_categoria'] ?? '';
        $this->producto_prioridad = $args['producto_prioridad'] ?? 'Media';
        $this->producto_comprado = $args['producto_comprado'] ?? 0;
        $this->producto_situacion = $args['producto_situacion'] ?? 1;
    }

    // Método adaptado para Informix - obtener productos pendientes
    public static function obtenerPendientes() {
        try {
            $sql = "SELECT producto_id, producto_nombre, producto_cantidad, producto_categoria, producto_prioridad, producto_comprado, producto_situacion 
                    FROM productos 
                    WHERE producto_situacion = 1 
                    AND producto_comprado = 0 
                    ORDER BY producto_categoria, producto_prioridad, producto_nombre";
            
            $resultado = self::$db->query($sql);
            $productos = [];
            
            if ($resultado) {
                while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    $productos[] = $row;
                }
                $resultado->closeCursor();
            }
            
            return $productos;
            
        } catch (Exception $e) {
            error_log("Error en obtenerPendientes: " . $e->getMessage());
            return [];
        }
    }

    // Método adaptado para Informix - obtener productos comprados
    public static function obtenerComprados() {
        try {
            $sql = "SELECT producto_id, producto_nombre, producto_cantidad, producto_categoria, producto_prioridad, producto_comprado, producto_situacion 
                    FROM productos 
                    WHERE producto_situacion = 1 
                    AND producto_comprado = 1 
                    ORDER BY producto_id DESC";
            
            $resultado = self::$db->query($sql);
            $productos = [];
            
            if ($resultado) {
                while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    $productos[] = $row;
                }
                $resultado->closeCursor();
            }
            
            return $productos;
            
        } catch (Exception $e) {
            error_log("Error en obtenerComprados: " . $e->getMessage());
            return [];
        }
    }

    // Verificar duplicados - adaptado para Informix
    public static function existeDuplicado($nombre, $categoria, $excluirId = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM productos 
                    WHERE producto_nombre = ? 
                    AND producto_categoria = ? 
                    AND producto_situacion = 1";
                    
            $params = [$nombre, $categoria];
            
            if ($excluirId) {
                $sql .= " AND producto_id != ?";
                $params[] = intval($excluirId);
            }
            
            $stmt = self::$db->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            
            return ($resultado['total'] ?? 0) > 0;
            
        } catch (Exception $e) {
            error_log("Error en existeDuplicado: " . $e->getMessage());
            return false;
        }
    }

    // Marcar como comprado - adaptado para Informix
    public static function marcarComprado($id) {
        try {
            $sql = "UPDATE productos 
                    SET producto_comprado = 1 
                    WHERE producto_id = ?";
            
            $stmt = self::$db->prepare($sql);
            $resultado = $stmt->execute([intval($id)]);
            $stmt->closeCursor();
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en marcarComprado: " . $e->getMessage());
            return false;
        }
    }

    // Desmarcar comprado - adaptado para Informix
    public static function desmarcarComprado($id) {
        try {
            $sql = "UPDATE productos 
                    SET producto_comprado = 0 
                    WHERE producto_id = ?";
            
            $stmt = self::$db->prepare($sql);
            $resultado = $stmt->execute([intval($id)]);
            $stmt->closeCursor();
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en desmarcarComprado: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar lógicamente - adaptado para Informix
    public static function eliminarLogico($id) {
        try {
            $sql = "UPDATE productos 
                    SET producto_situacion = 0 
                    WHERE producto_id = ?";
            
            $stmt = self::$db->prepare($sql);
            $resultado = $stmt->execute([intval($id)]);
            $stmt->closeCursor();
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en eliminarLogico: " . $e->getMessage());
            return false;
        }
    }

    // Crear producto - sobrescribir método heredado para Informix
    public function crear() {
        try {
            $sql = "INSERT INTO productos (producto_nombre, producto_cantidad, producto_categoria, producto_prioridad, producto_comprado, producto_situacion) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = self::$db->prepare($sql);
            $resultado = $stmt->execute([
                $this->producto_nombre,
                $this->producto_cantidad,
                $this->producto_categoria,
                $this->producto_prioridad,
                $this->producto_comprado,
                $this->producto_situacion
            ]);
            
            $stmt->closeCursor();
            
            return [
                'resultado' => $resultado,
                'id' => self::$db->lastInsertId()
            ];
            
        } catch (Exception $e) {
            error_log("Error en crear: " . $e->getMessage());
            throw $e;
        }
    }


    
}