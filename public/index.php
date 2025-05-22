<?php 
require_once __DIR__ . '/../includes/app.php';



use MVC\Router;
use Controllers\AppController;
use Controllers\CategoriasController;
use Controllers\PrioridadesController;
use Controllers\ProductosController;


$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);


$router->get('/', [AppController::class,'index']);

//ESTE ES EL URL PARA USUARIO

$router->get('/productos', [ProductosController::class, 'renderizarPagina']);

// productos 

$router->post('/productos/guardarAPI', [ProductosController::class, 'guardarAPI']);
$router->get('/productos/buscarAPI', [ProductosController::class, 'buscarAPI']);
$router->post('/productos/modificarAPI', [ProductosController::class, 'modificarAPI']);
$router->get('/productos/eliminar', [ProductosController::class, 'EliminarAPI']);


// categorias 


$router->get('/categorias', [CategoriasController::class, 'renderizarPagina']);
$router->post('/categorias/guardarAPI', [CategoriasController::class, 'guardarAPI']);
$router->get('/categorias/buscarAPI', [CategoriasController::class, 'buscarAPI']);
$router->post('/categorias/modificarAPI', [CategoriasController::class, 'modificarAPI']);
$router->get('/categorias/eliminar', [CategoriasController::class, 'EliminarAPI']);


// Prioridades


$router->get('/prioridades', [PrioridadesController::class, 'renderizarPagina']);
$router->post('/prioridades/guardarAPI', [PrioridadesController::class, 'guardarAPI']);
$router->get('/prioridades/buscarAPI', [PrioridadesController::class, 'buscarAPI']);
$router->post('/prioridades/modificarAPI', [PrioridadesController::class, 'modificarAPI']);
$router->get('/prioridades/eliminar', [PrioridadesController::class, 'EliminarAPI']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
