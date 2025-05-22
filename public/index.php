<?php 
require_once __DIR__ . '/../includes/app.php';

use Controllers\UsuarioController;
use Controllers\ProductoController;
use MVC\Router;
use Controllers\AppController;

// ✅ PRIMERO crear el router
$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

// ✅ DESPUÉS agregar las rutas
$router->get('/', [AppController::class,'index']);

// // RUTAS PARA USUARIOS (existentes)
// $router->get('/usuario', [UsuarioController::class, 'renderizarPagina']);
// $router->post('/usuarios/guardarAPI', [UsuarioController::class, 'guardarAPI']);
// $router->get('/usuarios/buscarAPI', [UsuarioController::class, 'buscarAPI']);
// $router->post('/usuarios/modificarAPI', [UsuarioController::class, 'modificarAPI']);
// $router->get('/usuarios/eliminar', [UsuarioController::class, 'EliminarAPI']);

// RUTAS PARA PRODUCTOS (nuevas)
$router->get('/productos', [ProductoController::class, 'renderizarPagina']);
$router->post('/productos/guardarAPI', [ProductoController::class, 'guardarAPI']);
$router->get('/productos/buscarAPI', [ProductoController::class, 'buscarAPI']);
$router->get('/productos/buscarCompradosAPI', [ProductoController::class, 'buscarCompradosAPI']);
$router->get('/productos/marcarComprado', [ProductoController::class, 'marcarCompradoAPI']);
$router->get('/productos/desmarcarComprado', [ProductoController::class, 'desmarcarCompradoAPI']);
$router->get('/productos/eliminar', [ProductoController::class, 'eliminarAPI']);

// Comprueba y valida las rutas
$router->comprobarRutas();