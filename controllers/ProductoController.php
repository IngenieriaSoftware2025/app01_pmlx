<?php

namespace Controllers;

use Exception;
use MVC\Router;

class ProductoController 
{
    public function renderizarPagina(Router $router)
    {
        $router->render('productos/index', []);
    }

    private static function getDB() {
        global $db;
        return $db;
    }

    public static function guardarAPI()
    {
        header("Content-type: application/json; charset=utf-8");
        
        try {
            $nombre = trim($_POST['producto_nombre'] ?? '');
            $cantidad = intval($_POST['producto_cantidad'] ?? 1);
            $categoria = trim($_POST['producto_categoria'] ?? '');
            $prioridad = trim($_POST['producto_prioridad'] ?? 'Media');
            
            if (empty($nombre)) {
                echo json_encode(['codigo' => 0, 'mensaje' => 'Nombre requerido']);
                return;
            }
            if (empty($categoria)) {
                echo json_encode(['codigo' => 0, 'mensaje' => 'Categoría requerida']);
                return;
            }
            
            $db = self::getDB();
            
            // Escapar strings para Informix
            $nombre = str_replace("'", "''", $nombre);
            $categoria = str_replace("'", "''", $categoria);
            $prioridad = str_replace("'", "''", $prioridad);
            
            // SIN producto_id porque ahora es SERIAL (auto-incremento)
            $sql = "INSERT INTO productos (producto_nombre, producto_cantidad, producto_categoria, producto_prioridad, producto_comprado, producto_situacion) VALUES ('$nombre', $cantidad, '$categoria', '$prioridad', 0, 1)";
            
            $resultado = $db->exec($sql);
            
            if ($resultado !== false && $resultado > 0) {
                echo json_encode([
                    'codigo' => 1, 
                    'mensaje' => 'Producto agregado exitosamente'
                ]);
            } else {
                $error = $db->errorInfo();
                echo json_encode([
                    'codigo' => 0, 
                    'mensaje' => 'Error al insertar producto',
                    'sql' => $sql,
                    'error' => $error[2] ?? 'Error desconocido'
                ]);
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage(),
                'linea' => $e->getLine()
            ]);
        }
    }
    
    public static function buscarAPI()
    {
        header("Content-type: application/json; charset=utf-8");
        
        try {
            $db = self::getDB();
            
            // Consulta simple para Informix
            $sql = "SELECT producto_id, producto_nombre, producto_cantidad, producto_categoria, producto_prioridad, producto_comprado, producto_situacion FROM productos WHERE producto_situacion = 1 AND producto_comprado = 0";
            
            $stmt = $db->prepare($sql);
            $stmt->execute();
            
            $productos = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $productos[] = [
                    'producto_id' => intval($row['producto_id'] ?? 0),
                    'producto_nombre' => trim($row['producto_nombre'] ?? ''),
                    'producto_cantidad' => intval($row['producto_cantidad'] ?? 0),
                    'producto_categoria' => trim($row['producto_categoria'] ?? ''),
                    'producto_prioridad' => trim($row['producto_prioridad'] ?? ''),
                ];
            }
            
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos obtenidos correctamente',
                'data' => $productos,
                'total' => count($productos)
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar: ' . $e->getMessage(),
                'linea' => $e->getLine()
            ]);
        }
    } 
    
    public static function buscarCompradosAPI()
    {
        header("Content-type: application/json; charset=utf-8");
        
        try {
            $db = self::getDB();
            
            $sql = "SELECT producto_id, producto_nombre, producto_cantidad, producto_categoria FROM productos WHERE producto_situacion = 1 AND producto_comprado = 1";
            
            $stmt = $db->prepare($sql);
            $stmt->execute();
            
            $productos = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $productos[] = [
                    'producto_id' => intval($row['producto_id'] ?? 0),
                    'producto_nombre' => trim($row['producto_nombre'] ?? ''),
                    'producto_cantidad' => intval($row['producto_cantidad'] ?? 0),
                    'producto_categoria' => trim($row['producto_categoria'] ?? '')
                ];
            }
            
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos comprados obtenidos',
                'data' => $productos
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage(),
                'linea' => $e->getLine()
            ]);
        }
    }
    
    public static function marcarCompradoAPI()
    {
        header("Content-type: application/json; charset=utf-8");
        
        try {
            $id = intval($_GET['id'] ?? 0);
            
            if ($id <= 0) {
                echo json_encode(['codigo' => 0, 'mensaje' => 'ID inválido']);
                return;
            }
            
            $db = self::getDB();
            $sql = "UPDATE productos SET producto_comprado = 1 WHERE producto_id = $id";
            
            $resultado = $db->exec($sql);
            
            echo json_encode([
                'codigo' => 1, 
                'mensaje' => 'Producto marcado como comprado'
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    public static function desmarcarCompradoAPI()
    {
        header("Content-type: application/json; charset=utf-8");
        
        try {
            $id = intval($_GET['id'] ?? 0);
            
            if ($id <= 0) {
                echo json_encode(['codigo' => 0, 'mensaje' => 'ID inválido']);
                return;
            }
            
            $db = self::getDB();
            $sql = "UPDATE productos SET producto_comprado = 0 WHERE producto_id = $id";
            
            $resultado = $db->exec($sql);
            
            echo json_encode([
                'codigo' => 1, 
                'mensaje' => 'Producto devuelto a la lista'
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    public static function eliminarAPI()
    {
        header("Content-type: application/json; charset=utf-8");
        
        try {
            $id = intval($_GET['id'] ?? 0);
            
            if ($id <= 0) {
                echo json_encode(['codigo' => 0, 'mensaje' => 'ID inválido']);
                return;
            }
            
            $db = self::getDB();
            $sql = "UPDATE productos SET producto_situacion = 0 WHERE producto_id = $id";
            
            $resultado = $db->exec($sql);
            
            echo json_encode([
                'codigo' => 1, 
                'mensaje' => 'Producto eliminado correctamente'
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}