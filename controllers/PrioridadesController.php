<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Prioridades;
use MVC\Router;

class PrioridadesController extends ActiveRecord
{

    public function renderizarPagina(Router $router)
    {
        $router->render('prioridades/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        $_POST['nivel'] = htmlspecialchars($_POST['nivel']);

        $cantidad_nivel = strlen($_POST['nivel']);

        if ($cantidad_nivel < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nivel de prioridad debe tener al menos 2 caracteres'
            ]);
            return;
        }

        // Validar que el nivel sea uno de los permitidos
        $niveles_permitidos = ['Alta', 'Media', 'Baja'];
        if (!in_array($_POST['nivel'], $niveles_permitidos)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nivel debe ser: Alta, Media o Baja'
            ]);
            return;
        }

        try {
            $data = new Prioridades([
                'nivel' => $_POST['nivel']
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Prioridad registrada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la prioridad',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {
        try {
            $sql = "SELECT * FROM prioridades ORDER BY 
                    CASE 
                        WHEN nivel = 'Alta' THEN 1
                        WHEN nivel = 'Media' THEN 2
                        WHEN nivel = 'Baja' THEN 3
                        ELSE 4
                    END";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Prioridades obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las prioridades',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['prioridad_id'];

        $_POST['nivel'] = htmlspecialchars($_POST['nivel']);

        $cantidad_nivel = strlen($_POST['nivel']);

        if ($cantidad_nivel < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nivel de prioridad debe tener al menos 2 caracteres'
            ]);
            return;
        }

        // Validar que el nivel sea uno de los permitidos
        $niveles_permitidos = ['Alta', 'Media', 'Baja'];
        if (!in_array($_POST['nivel'], $niveles_permitidos)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nivel debe ser: Alta, Media o Baja'
            ]);
            return;
        }

        try {
            $data = Prioridades::find($id);
            $data->sincronizar([
                'nivel' => $_POST['nivel']
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Prioridad modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar la prioridad',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function EliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            // Verificar si hay productos asociados a esta prioridad
            $sql = "SELECT COUNT(*) as total FROM productos WHERE prioridad_id = {$id}";
            $resultado = self::fetchArray($sql);
            
            if ($resultado[0]['total'] > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se puede eliminar la prioridad porque tiene productos asociados'
                ]);
                return;
            }

            $ejecutar = Prioridades::EliminarPrioridades($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Prioridad eliminada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la prioridad',
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

            $sql = "SELECT * FROM prioridades WHERE prioridad_id = {$id}";
            $data = self::fetchArray($sql);

            if (empty($data)) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Prioridad no encontrada'
                ]);
                return;
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Prioridad obtenida correctamente',
                'data' => $data[0]
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener la prioridad',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}