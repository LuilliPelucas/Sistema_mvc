<?php
/**
 * Clase Controller (Controlador Base)
 * 
 * Clase padre de todos los controladores del sistema
 * Proporciona métodos comunes para cargar vistas y modelos
 * 
 * @author Tu Nombre
 * @version 1.0
 */

class Controller {
    
    /**
     * Carga una vista
     * 
     * @param string $vista Nombre de la vista (sin extensión .php)
     * @param array $datos Datos a pasar a la vista
     * @return void
     */
    protected function loadView($vista, $datos = []) {
        // Extraer datos como variables
        extract($datos);
        
        // Ruta completa de la vista
        $rutaVista = VIEWS_PATH . $vista . '.php';
        
        // Verificar si existe la vista
        if (file_exists($rutaVista)) {
            require_once $rutaVista;
        } else {
            die("Error: La vista '{$vista}' no existe en {$rutaVista}");
        }
    }
    
    /**
     * Carga un modelo
     * 
     * @param string $modelo Nombre del modelo (sin extensión .php)
     * @return object Instancia del modelo
     */
    protected function loadModel($modelo) {
        // Ruta completa del modelo
        $rutaModelo = MODELS_PATH . $modelo . '.php';
        
        // Verificar si existe el modelo
        if (file_exists($rutaModelo)) {
            require_once $rutaModelo;
            return new $modelo();
        } else {
            die("Error: El modelo '{$modelo}' no existe");
        }
    }
    
    /**
     * Redirecciona a una URL
     * 
     * @param string $url URL de destino (relativa a BASE_URL)
     * @return void
     */
    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }
    
    /**
     * Devuelve una respuesta JSON
     * Útil para peticiones AJAX
     * 
     * @param array $data Datos a convertir en JSON
     * @param int $statusCode Código de estado HTTP
     * @return void
     */
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Obtiene datos de $_POST de forma segura
     * 
     * @param string $key Clave del dato
     * @param mixed $default Valor por defecto si no existe
     * @return mixed Valor del POST o valor por defecto
     */
    protected function post($key, $default = null) {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }
    
    /**
     * Obtiene datos de $_GET de forma segura
     * 
     * @param string $key Clave del dato
     * @param mixed $default Valor por defecto si no existe
     * @return mixed Valor del GET o valor por defecto
     */
    protected function get($key, $default = null) {
        return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
    }
    
    /**
     * Valida si una petición es POST
     * 
     * @return bool True si es POST, false en caso contrario
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Valida si una petición es AJAX
     * 
     * @return bool True si es AJAX, false en caso contrario
     */
    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
?>