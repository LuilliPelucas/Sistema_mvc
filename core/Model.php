<?php
/**
 * Clase Model (Modelo Base)
 * 
 * Clase padre de todos los modelos del sistema
 * Proporciona métodos comunes para interactuar con la base de datos
 * 
 * @author Tu Nombre
 * @version 1.0
 */

class Model {
    
    // Conexión a la base de datos
    protected $db;
    
    // Nombre de la tabla (debe ser definido en cada modelo hijo)
    protected $tabla;
    
    // Llave primaria de la tabla (puede ser sobrescrita)
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     * Inicializa la conexión a la base de datos
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConexion();
    }
    
    /**
     * Obtiene todos los registros de la tabla
     * 
     * @return array Array de registros
     */
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->tabla}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en getAll(): " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene un registro por su ID
     * 
     * @param int $id ID del registro
     * @return array|false Registro encontrado o false
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM {$this->tabla} WHERE {$this->primaryKey} = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error en getById(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Inserta un nuevo registro en la tabla
     * 
     * @param array $datos Array asociativo con los datos a insertar
     * @return int|false ID del registro insertado o false en caso de error
     */
    public function insert($datos) {
        try {
            // Preparar columnas y valores
            $columnas = implode(', ', array_keys($datos));
            $valores = ':' . implode(', :', array_keys($datos));
            
            $sql = "INSERT INTO {$this->tabla} ({$columnas}) VALUES ({$valores})";
            $stmt = $this->db->prepare($sql);
            
            // Bind de parámetros
            foreach ($datos as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            
            $stmt->execute();
            return $this->db->lastInsertId();
            
        } catch (PDOException $e) {
            error_log("Error en insert(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualiza un registro existente
     * 
     * @param int $id ID del registro a actualizar
     * @param array $datos Array asociativo con los datos a actualizar
     * @return bool True si se actualizó correctamente, false en caso contrario
     */
    public function update($id, $datos) {
        try {
            // Preparar SET clause
            $set = [];
            foreach ($datos as $key => $value) {
                $set[] = "{$key} = :{$key}";
            }
            $setClause = implode(', ', $set);
            
            $sql = "UPDATE {$this->tabla} SET {$setClause} WHERE {$this->primaryKey} = :id";
            $stmt = $this->db->prepare($sql);
            
            // Bind de parámetros
            foreach ($datos as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error en update(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Elimina un registro de la tabla
     * 
     * @param int $id ID del registro a eliminar
     * @return bool True si se eliminó correctamente, false en caso contrario
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->tabla} WHERE {$this->primaryKey} = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error en delete(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cuenta el total de registros en la tabla
     * 
     * @return int Número de registros
     */
    public function count() {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error en count(): " . $e->getMessage());
            return 0;
        }
    }
}
?>