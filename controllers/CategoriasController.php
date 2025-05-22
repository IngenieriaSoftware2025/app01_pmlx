<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Categorias;
use MVC\Router;

class CategoriasController extends ActiveRecord
{

    public function renderizarPagina(Router $router)
    {
        $router->render('categorias/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        $_POST['nombre'] = htmlspecialchars($_POST['nombre']);

        $cantidad_nombre = strlen($_POST['nombre']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la categoría debe tener al menos 2 caracteres'
            ]);
            return;
        }

        try {
            $data = new Categorias([
                'nombre' => $_POST['nombre']
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Categoría registrada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la categoría',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {
        try {
            $sql = "SELECT * FROM categorias ORDER BY nombre";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Categorías obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las categorías',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['categoria_id'];

        $_POST['nombre'] = htmlspecialchars($_POST['nombre']);

        $cantidad_nombre = strlen($_POST['nombre']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la categoría debe tener al menos 2 caracteres'
            ]);
            return;
        }

        try {
            $data = Categorias::find($id);
            $data->sincronizar([
                'nombre' => $_POST['nombre']
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Categoría modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar la categoría',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function EliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            // Verificar si hay productos asociados a esta categoría
            $sql = "SELECT COUNT(*) as total FROM productos WHERE categoria_id = {$id}";
            $resultado = self::fetchArray($sql);
            
            if ($resultado[0]['total'] > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se puede eliminar la categoría porque tiene productos asociados'
                ]);
                return;
            }

            $ejecutar = Categorias::EliminarCategorias($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Categoría eliminada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la categoría',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarPorIdAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe proporcionar un ID válido'
                ]);
                return;
            }

            $sql = "SELECT * FROM categorias WHERE categoria_id = {$id}";
            $data = self::fetchArray($sql);

            if (empty($data)) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Categoría no encontrada'
                ]);
                return;
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Categoría obtenida correctamente',
                'data' => $data[0]
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener la categoría',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}