<?php
/**
 * Modelo Proveedor
 * 
 * Maneja las operaciones de la tabla 'proveedores'
 * Extiende de la clase Model para heredar operaciones CRUD básicas
 * 
 * @author Tu Nombre
 * @version 1.0
 */

class Proveedor extends Model {
    
    /**
     * Constructor
     * Define el nombre de la tabla y la llave primaria
     */
    public function __construct() {
        parent::__construct();
        $this->tabla = 'proveedores';
        $this->primaryKey = 'proveedoresid';
    }
    
    /**
     * Busca proveedores por nombre o clave
     * Útil para búsquedas en tiempo real
     * 
     * @param string $termino Término de búsqueda
     * @return array Array de proveedores encontrados
     */
    public function buscar($termino) {
        try {
            $sql = "SELECT * FROM {$this->tabla} 
                    WHERE nombreproveedor LIKE :termino 
                    OR claveproveedor LIKE :termino
                    ORDER BY nombreproveedor ASC";
            
            $stmt = $this->db->prepare($sql);
            $terminoBusqueda = "%{$termino}%";
            $stmt->bindParam(':termino', $terminoBusqueda, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en buscar(): " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Valida si una clave de proveedor ya existe
     * 
     * @param string $clave Clave a validar
     * @param int $excluirId ID a excluir de la validación (útil en edición)
     * @return bool True si existe, false si no existe
     */
    public function claveExiste($clave, $excluirId = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} 
                    WHERE claveproveedor = :clave";
            
            if ($excluirId !== null) {
                $sql .= " AND {$this->primaryKey} != :id";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':clave', $clave, PDO::PARAM_STR);
            
            if ($excluirId !== null) {
                $stmt->bindParam(':id', $excluirId, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $result = $stmt->fetch();
            
            return $result['total'] > 0;
        } catch (PDOException $e) {
            error_log("Error en claveExiste(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene proveedores por ciudad
     * 
     * @param string $ciudad Nombre de la ciudad
     * @return array Array de proveedores
     */
    public function getPorCiudad($ciudad) {
        try {
            $sql = "SELECT * FROM {$this->tabla} 
                    WHERE ciudad = :ciudad
                    ORDER BY nombreproveedor ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ciudad', $ciudad, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en getPorCiudad(): " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Valida los datos del proveedor antes de guardar
     * 
     * @param array $datos Datos a validar
     * @return array Array con 'valido' (bool) y 'errores' (array)
     */
    public function validar($datos) {
        $errores = [];

        // Validar clave proveedor (requerido)
        if (empty($datos['claveproveedor'])) {
            $errores[] = "La clave del proveedor es requerida";
        }
        
        // Validar nombre proveedor (requerido)
        if (empty($datos['nombreproveedor'])) {
            $errores[] = "El nombre del proveedor es requerido";
        }
        
        // Validar longitud de campos
        if (strlen($datos['claveproveedor']) > 45) {
            $errores[] = "La clave no puede exceder 45 caracteres";
        }

        if (strlen($datos['nombreproveedor']) > 245) {
            $errores[] = "El nombre no puede exceder 245 caracteres";
        }
        
        return [
            'valido' => empty($errores),
            'errores' => $errores
        ];
    }
}
?>