<?php
/**
 * Bootstrap de la Aplicación
 * 
 * Punto de entrada principal del sistema
 * Maneja el enrutamiento y la carga de controladores
 * 
 * @author Tu Nombre
 * @version 1.0
 */

// Iniciar sesión
session_start();

// Cargar configuración
require_once 'config/config.php';

/**
 * Obtener la URL solicitada
 * Ejemplo: /clientes/editar/5
 */
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

/**
 * Determinar el controlador
 * Si no se especifica, usar el controlador por defecto
 */
$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : DEFAULT_CONTROLLER;
$controllerFile = CONTROLLERS_PATH . $controllerName . '.php';

// Verificar si existe el controlador
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
} else {
    die("Error 404: Controlador '{$controllerName}' no encontrado");
}

/**
 * Determinar el método (acción)
 * Si no se especifica, usar el método por defecto
 */
$method = !empty($url[1]) ? $url[1] : DEFAULT_METHOD;

// Verificar si existe el método en el controlador
if (!method_exists($controller, $method)) {
    die("Error 404: El método '{$method}' no existe en el controlador '{$controllerName}'");
}

/**
 * Obtener parámetros adicionales
 * Ejemplo: /clientes/editar/5 -> parámetros = [5]
 */
$params = $url ? array_slice($url, 2) : [];

/**
 * Llamar al método del controlador con los parámetros
 */
call_user_func_array([$controller, $method], $params);
?>