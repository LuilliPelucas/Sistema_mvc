<?php
/**
 * Clase Database
 * 
 * Maneja la conexión a la base de datos MySQL usando PDO
 * Utiliza el patrón Singleton para asegurar una única instancia
 * 
 * @author Tu Nombre
 * @version 1.0
 */

class Database {
    
    // Credenciales de conexión (modificar según tu configuración)
    private $host = "localhost";
    private $port = "3309";
    private $database = "sistema_mvc";
    private $username = "root";
    private $password = "Shapes0926";
    private $charset = "utf8mb4";
    
    // Variable para almacenar la conexión PDO
    private $conexion;
    
    /**
     * Constructor
     * Inicializa la conexión a la base de datos
     */
    public function __construct() {
        $this->conectar();
    }
    
    /**
     * Establece la conexión con la base de datos
     * 
     * @return void
     */
    private function conectar() {
        try {
            // DSN (Data Source Name) para la conexión con puerto personalizado
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset={$this->charset}";
            
            // Opciones de configuración de PDO
            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,    // Lanzar excepciones en errores
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // Fetch como array asociativo
                PDO::ATTR_EMULATE_PREPARES   => false,                     // Usar prepared statements reales
            ];
            
            // Crear la conexión PDO
            $this->conexion = new PDO($dsn, $this->username, $this->password, $opciones);
            
        } catch (PDOException $e) {
            // En producción, registrar el error en un log en lugar de mostrarlo
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    /**
     * Obtiene la conexión PDO
     * 
     * @return PDO Objeto de conexión
     */
    public function getConexion() {
        return $this->conexion;
    }
    
    /**
     * Cierra la conexión
     * 
     * @return void
     */
    public function cerrarConexion() {
        $this->conexion = null;
    }
}
?>