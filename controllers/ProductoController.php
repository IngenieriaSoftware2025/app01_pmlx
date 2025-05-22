<?php

namespace Controllers;

use Exception;
use MVC\Router;
use PDO; 

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
    ob_clean();
    header("Content-type: application/json; charset=utf-8");

     error_log("Parámetro tipo recibido: " . ($_GET['tipo'] ?? 'NO DEFINIDO'));

    try {
        $db = self::getDB();

         $tipo = $_GET['tipo'] ?? 'pendientes';

         error_log("Variable tipo asignada: " . $tipo);
        
        $sql = "SELECT * FROM productos";
        $resultado = $db->query($sql);
        
        $productos_para_lista = [];
        
        if ($resultado) {
            while ($row = $resultado->fetch(\PDO::FETCH_ASSOC)) {
                if ($row) {
                    // Obtener valores con fallback a mayúsculas
                    $situacion = intval($row['producto_situacion'] ?? $row['PRODUCTO_SITUACION'] ?? 0);
                    $comprado = intval($row['producto_comprado'] ?? $row['PRODUCTO_COMPRADO'] ?? 0);
                    
                    // Solo productos activos y NO comprados
                    if ($situacion == 1 && $comprado == 0) {
                        $productos_para_lista[] = [
                            'producto_id' => intval($row['producto_id'] ?? $row['PRODUCTO_ID'] ?? 0),
                            'producto_nombre' => trim($row['producto_nombre'] ?? $row['PRODUCTO_NOMBRE'] ?? ''),
                            'producto_cantidad' => intval($row['producto_cantidad'] ?? $row['PRODUCTO_CANTIDAD'] ?? 0),
                            'producto_categoria' => trim($row['producto_categoria'] ?? $row['PRODUCTO_CATEGORIA'] ?? ''),
                            'producto_prioridad' => trim($row['producto_prioridad'] ?? $row['PRODUCTO_PRIORIDAD'] ?? 'Media')
                        ];
                    }
                }
            }
        }
        
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Productos obtenidos correctamente',
            'data' => $productos_para_lista,
            'total' => count($productos_para_lista)
        ]);
        exit;
        
    } catch (Exception $e) {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => $e->getMessage()
        ]);
        exit;
    }
}



public static function buscarCompradosAPI()
{
    ob_clean();
    header("Content-type: application/json; charset=utf-8");

    try {
        $db = self::getDB();
        
        $sql = "SELECT * FROM productos WHERE producto_situacion = 1 AND producto_comprado = 1";
        $resultado = $db->query($sql);
        
        $productos_comprados = []; // ✅ Variable correcta
        
        if ($resultado) {
            while ($row = $resultado->fetch(\PDO::FETCH_ASSOC)) {
                if ($row) {
                    $productos_comprados[] = [ // ✅ Usar la variable correcta
                        'producto_id' => intval($row['producto_id'] ?? $row['PRODUCTO_ID'] ?? 0),
                        'producto_nombre' => trim($row['producto_nombre'] ?? $row['PRODUCTO_NOMBRE'] ?? ''),
                        'producto_cantidad' => intval($row['producto_cantidad'] ?? $row['PRODUCTO_CANTIDAD'] ?? 0),
                        'producto_categoria' => trim($row['producto_categoria'] ?? $row['PRODUCTO_CATEGORIA'] ?? ''),
                        'producto_prioridad' => trim($row['producto_prioridad'] ?? $row['PRODUCTO_PRIORIDAD'] ?? 'Media')
                    ];
                }
            }
        }
        
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Productos comprados obtenidos correctamente',
            'data' => $productos_comprados, // ✅ Variable correcta
            'total' => count($productos_comprados)
        ]);
        exit;
        
    } catch (Exception $e) {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'Error: ' . $e->getMessage(),
            'data' => []
        ]);
        exit;
    }
}


public function regresarPendiente() {
    try {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            throw new Exception('ID requerido');
        }
        
        $producto = Producto::find($id);
        if (!$producto) {
            throw new Exception('Producto no encontrado');
        }
        
        $producto->fecha_comprado = null;
        $producto->save();
        
        return $this->json([
            'codigo' => 1,
            'mensaje' => 'Producto regresado a pendientes'
        ]);
    } catch (Exception $e) {
        return $this->json([
            'codigo' => 0,
            'mensaje' => 'Error: ' . $e->getMessage()
        ]);
    }
}





public function eliminarComprado() {
    try {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            throw new Exception('ID requerido');
        }
        
        $producto = Producto::find($id);
        if (!$producto) {
            throw new Exception('Producto no encontrado');
        }
        
        $producto->delete();
        
        return $this->json([
            'codigo' => 1,
            'mensaje' => 'Producto eliminado definitivamente'
        ]);
    } catch (Exception $e) {
        return $this->json([
            'codigo' => 0,
            'mensaje' => 'Error: ' . $e->getMessage()
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
        
        // Buscar TODOS los productos comprados
        $sqlComprados = "SELECT * FROM productos WHERE producto_situacion = 1 AND producto_comprado = 1";
        $resultadoComprados = $db->query($sqlComprados);
        $productosComprados = [];
        
        if ($resultadoComprados) {
            while ($row = $resultadoComprados->fetch(\PDO::FETCH_ASSOC)) {
                $productosComprados[] = [
                    'producto_id' => intval($row['producto_id'] ?? $row['PRODUCTO_ID'] ?? 0),
                    'producto_nombre' => trim($row['producto_nombre'] ?? $row['PRODUCTO_NOMBRE'] ?? ''),
                    'producto_cantidad' => intval($row['producto_cantidad'] ?? $row['PRODUCTO_CANTIDAD'] ?? 0),
                    'producto_categoria' => trim($row['producto_categoria'] ?? $row['PRODUCTO_CATEGORIA'] ?? ''),
                    'producto_prioridad' => trim($row['producto_prioridad'] ?? $row['PRODUCTO_PRIORIDAD'] ?? 'Media')
                ];
            }
        }
        
        echo json_encode([
            'codigo' => 1, 
            'mensaje' => 'Producto marcado como comprado',
            'productos_comprados' => $productosComprados
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
