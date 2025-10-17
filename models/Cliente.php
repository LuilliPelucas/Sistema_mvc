<?php
/**
 * Modelo Cliente
 * 
 * Maneja las operaciones de la tabla 'clientes'
 * Extiende de la clase Model para heredar operaciones CRUD básicas
 * 
 * @author Tu Nombre
 * @version 1.0
 */

class Cliente extends Model {
    
    /**
     * Constructor
     * Define el nombre de la tabla y la llave primaria
     */
    public function __construct() {
        parent::__construct();
        $this->tabla = 'clientes';
        $this->primaryKey = 'clientesid';
    }
    
    /**
     * Busca clientes por nombre o clave
     * Útil para búsquedas en tiempo real
     * 
     * @param string $termino Término de búsqueda
     * @return array Array de clientes encontrados
     */
    public function buscar($termino) {
        try {
            $sql = "SELECT * FROM {$this->tabla} 
                    WHERE nombrecliente LIKE :termino 
                    OR clavecliente LIKE :termino
                    ORDER BY nombrecliente ASC";
            
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
     * Valida si una clave de cliente ya existe
     * 
     * @param string $clave Clave a validar
     * @param int $excluirId ID a excluir de la validación (útil en edición)
     * @return bool True si existe, false si no existe
     */
    public function claveExiste($clave, $excluirId = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} 
                    WHERE clavecliente = :clave";
            
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
     * Obtiene clientes por ciudad
     * 
     * @param string $ciudad Nombre de la ciudad
     * @return array Array de clientes
     */
    public function getPorCiudad($ciudad) {
        try {
            $sql = "SELECT * FROM {$this->tabla} 
                    WHERE ciudad = :ciudad
                    ORDER BY nombrecliente ASC";
            
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
     * Valida los datos del cliente antes de guardar
     * 
     * @param array $datos Datos a validar
     * @return array Array con 'valido' (bool) y 'errores' (array)
     */
    public function validar($datos) {
        $errores = [];
        
        // Validar clave cliente (requerido)
        if (empty($datos['clavecliente'])) {
            $errores[] = "La clave del cliente es requerida";
        }
        
        // Validar nombre cliente (requerido)
        if (empty($datos['nombrecliente'])) {
            $errores[] = "El nombre del cliente es requerido";
        }
        
        // Validar longitud de campos
        if (strlen($datos['clavecliente']) > 45) {
            $errores[] = "La clave no puede exceder 45 caracteres";
        }
        
        if (strlen($datos['nombrecliente']) > 245) {
            $errores[] = "El nombre no puede exceder 245 caracteres";
        }
        
        return [
            'valido' => empty($errores),
            'errores' => $errores
        ];
    }
}
?>