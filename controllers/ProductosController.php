<?php

namespace Controllers;

use DateTime; // ← AGREGAR ESTE IMPORT
use Exception;
use Model\ActiveRecord;
use Model\Productos;
use MVC\Router;

class ProductosController extends ActiveRecord
{

    public function renderizarPagina(Router $router)
    {
        $router->render('productos/index', []);
    }

    public static function guardarAPI()
    {

        getHeadersApi();

        $_POST['productos_nombre'] = htmlspecialchars($_POST['productos_nombre']);

        $cantidad_nombre = strlen($_POST['productos_nombre']);

        if ($cantidad_nombre < 2) {

            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de digitos que debe de contener el nombre del producto debe de ser mayor a dos'
            ]);
            return;
        }

        $_POST['productos_cantidad'] = filter_var($_POST['productos_cantidad'], FILTER_VALIDATE_INT);

        if (!$_POST['productos_cantidad'] || $_POST['productos_cantidad'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad del producto debe ser un número entero mayor o igual a 0'
            ]);
            return;
        }

        $_POST['categoria_id'] = filter_var($_POST['categoria_id'], FILTER_VALIDATE_INT);

        if (!$_POST['categoria_id'] || $_POST['categoria_id'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una categoría válida'
            ]);
            return;
        }

        // ← AGREGAR VALIDACIÓN PARA PRIORIDAD
        $_POST['prioridad_id'] = filter_var($_POST['prioridad_id'], FILTER_VALIDATE_INT);

        if (!$_POST['prioridad_id'] || $_POST['prioridad_id'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una prioridad válida'
            ]);
            return;
        }

        // Validar productos_comprado (0 o 1) - HACER OPCIONAL
        if (isset($_POST['productos_comprado'])) {
            $_POST['productos_comprado'] = filter_var($_POST['productos_comprado'], FILTER_VALIDATE_INT);
            if ($_POST['productos_comprado'] !== 0 && $_POST['productos_comprado'] !== 1) {
                $_POST['productos_comprado'] = 0; // Valor por defecto
            }
        } else {
            $_POST['productos_comprado'] = 0; // Por defecto no comprado
        }

        // Validar fecha de compra si el producto está comprado
        if ($_POST['productos_comprado'] == 1) {
            if (empty($_POST['productos_fecha_compra'])) {
                $_POST['productos_fecha_compra'] = date('Y-m-d'); // ← USAR FECHA ACTUAL SI NO SE PROPORCIONA
            } else {
                $fecha = DateTime::createFromFormat('Y-m-d', $_POST['productos_fecha_compra']); // ← CAMBIAR Date POR DateTime
                if (!$fecha) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'El formato de fecha debe ser YYYY-MM-DD'
                    ]);
                    return;
                }
            }
        } else {
            $_POST['productos_fecha_compra'] = null;
        }

        try {

            $data = new Productos([
                'productos_nombre' => $_POST['productos_nombre'],
                'productos_cantidad' => $_POST['productos_cantidad'],
                'categoria_id' => $_POST['categoria_id'],
                'prioridad_id' => $_POST['prioridad_id'], // ← AGREGAR ESTE CAMPO
                'productos_comprado' => $_POST['productos_comprado'],
                'productos_fecha_compra' => $_POST['productos_fecha_compra']
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito, el producto ha sido registrado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {

        try {

            // ← CORREGIR LA CONSULTA SQL PARA INCLUIR PRIORIDADES
            $sql = "SELECT p.*, c.nombre as categoria_nombre, pr.nivel as prioridad_nivel 
                   FROM productos p 
                   LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                   LEFT JOIN prioridades pr ON p.prioridad_id = pr.prioridad_id 
                   ORDER BY p.productos_nombre";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los productos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {

        getHeadersApi();

        $id = $_POST['productos_id'];

        $_POST['productos_nombre'] = htmlspecialchars($_POST['productos_nombre']);

        $cantidad_nombre = strlen($_POST['productos_nombre']);

        if ($cantidad_nombre < 2) {

            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de digitos que debe de contener el nombre del producto debe de ser mayor a dos'
            ]);
            return;
        }

        $_POST['productos_cantidad'] = filter_var($_POST['productos_cantidad'], FILTER_VALIDATE_INT);

        if (!$_POST['productos_cantidad'] || $_POST['productos_cantidad'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad del producto debe ser un número entero mayor o igual a 0'
            ]);
            return;
        }

        $_POST['categoria_id'] = filter_var($_POST['categoria_id'], FILTER_VALIDATE_INT);

        if (!$_POST['categoria_id'] || $_POST['categoria_id'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una categoría válida'
            ]);
            return;
        }

        // ← AGREGAR VALIDACIÓN PARA PRIORIDAD EN MODIFICAR
        $_POST['prioridad_id'] = filter_var($_POST['prioridad_id'], FILTER_VALIDATE_INT);

        if (!$_POST['prioridad_id'] || $_POST['prioridad_id'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una prioridad válida'
            ]);
            return;
        }

        // Validar productos_comprado (0 o 1)
        if (isset($_POST['productos_comprado'])) {
            $_POST['productos_comprado'] = filter_var($_POST['productos_comprado'], FILTER_VALIDATE_INT);
            if ($_POST['productos_comprado'] !== 0 && $_POST['productos_comprado'] !== 1) {
                $_POST['productos_comprado'] = 0; // Valor por defecto
            }
        } else {
            $_POST['productos_comprado'] = 0;
        }

        // Validar fecha de compra si el producto está comprado
        if ($_POST['productos_comprado'] == 1) {
            if (empty($_POST['productos_fecha_compra'])) {
                $_POST['productos_fecha_compra'] = date('Y-m-d'); // ← USAR FECHA ACTUAL SI NO SE PROPORCIONA
            } else {
                $fecha = DateTime::createFromFormat('Y-m-d', $_POST['productos_fecha_compra']); // ← CAMBIAR Date POR DateTime
                if (!$fecha) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'El formato de fecha debe ser YYYY-MM-DD'
                    ]);
                    return;
                }
            }
        } else {
            $_POST['productos_fecha_compra'] = null;
        }

        try {

            $data = Productos::find($id);
            $data->sincronizar([
                'productos_nombre' => $_POST['productos_nombre'],
                'productos_cantidad' => $_POST['productos_cantidad'],
                'categoria_id' => $_POST['categoria_id'],
                'prioridad_id' => $_POST['prioridad_id'], // ← AGREGAR ESTE CAMPO
                'productos_comprado' => $_POST['productos_comprado'],
                'productos_fecha_compra' => $_POST['productos_fecha_compra']
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información del producto ha sido modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function EliminarAPI()
    {

        try {

            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $ejecutar = Productos::EliminarProductos($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El producto ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Eliminar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarPorCategoriaAPI()
    {
        try {

            $categoria_id = filter_var($_GET['categoria_id'], FILTER_SANITIZE_NUMBER_INT);

            if (!$categoria_id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe proporcionar un ID de categoría válido'
                ]);
                return;
            }

            // ← CORREGIR LA CONSULTA SQL AQUÍ TAMBIÉN
            $sql = "SELECT p.*, c.nombre as categoria_nombre, pr.nivel as prioridad_nivel 
                   FROM productos p 
                   LEFT JOIN categorias c ON p.categoria_id = c.categoria_id 
                   LEFT JOIN prioridades pr ON p.prioridad_id = pr.prioridad_id 
                   WHERE p.categoria_id = {$categoria_id}
                   ORDER BY p.productos_nombre";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los productos por categoría',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function marcarCompradoAPI()
    {
        getHeadersApi();

        try {

            $id = $_POST['productos_id'];
            $fecha_compra = date('Y-m-d'); // Fecha actual

            $data = Productos::find($id);
            $data->sincronizar([
                'productos_comprado' => 1,
                'productos_fecha_compra' => $fecha_compra
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El producto ha sido marcado como comprado'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al marcar producto como comprado',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}