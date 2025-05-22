<?php 
require_once __DIR__ . '/../includes/app.php';


use Controllers\ProductoController;
use MVC\Router;
use Controllers\AppController;

// PRIMERO crear el router
$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

//  DESPUÉS agregar las rutas
$router->get('/', [AppController::class,'index']);


// RUTAS PARA PRODUCTOS (nuevas)
$router->get('/productos', [ProductoController::class, 'renderizarPagina']);
$router->post('/productos/guardarAPI', [ProductoController::class, 'guardarAPI']);
$router->get('/productos/buscarAPI', [ProductoController::class, 'buscarAPI']);
$router->get('/productos/buscarCompradosAPI', [ProductoController::class, 'buscarCompradosAPI']);
$router->get('/productos/marcarComprado', [ProductoController::class, 'marcarCompradoAPI']);
$router->get('/productos/desmarcarComprado', [ProductoController::class, 'desmarcarCompradoAPI']);
$router->get('/productos/eliminar', [ProductoController::class, 'eliminarAPI']);
$router->get('/productos/buscarCompradosTEST', [ProductoController::class, 'buscarCompradosTEST']);






// Comprueba y valida las rutas
$router->comprobarRutas();