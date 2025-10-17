<?php
/**
 * Modelo ConfiguracionEmpresa
 * Maneja la configuración de la empresa
 */

class ConfiguracionEmpresa extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->tabla = 'configuracion_empresa';
        $this->primaryKey = 'id';
    }
    
    /**
     * Obtener configuración de la empresa
     * Siempre devuelve el registro con ID = 1
     * 
     * @return array Configuración de la empresa
     */
    public function getConfiguracion() {
        try {
            $sql = "SELECT * FROM {$this->tabla} WHERE id = 1 LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $config = $stmt->fetch();
            
            // Si no existe, crear registro por defecto
            if (!$config) {
                $this->crearConfiguracionPorDefecto();
                // Intentar obtener nuevamente
                $stmt->execute();
                $config = $stmt->fetch();
            }
            
            return $config;
            
        } catch (PDOException $e) {
            error_log("Error en getConfiguracion(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar configuración de la empresa
     * 
     * @param array $datos Datos a actualizar
     * @return bool True si se actualizó correctamente
     */
    public function actualizarConfiguracion($datos) {
        try {
            $sql = "UPDATE {$this->tabla} SET
                    nombre_empresa = :nombre_empresa,
                    rfc = :rfc,
                    direccion = :direccion,
                    colonia = :colonia,
                    ciudad = :ciudad,
                    estado = :estado,
                    codigo_postal = :codigo_postal,
                    telefono = :telefono,
                    email = :email,
                    sitio_web = :sitio_web,
                    logo_path = :logo_path
                    WHERE id = 1";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':nombre_empresa', $datos['nombre_empresa'], PDO::PARAM_STR);
            $stmt->bindValue(':rfc', $datos['rfc'], PDO::PARAM_STR);
            $stmt->bindValue(':direccion', $datos['direccion'], PDO::PARAM_STR);
            $stmt->bindValue(':colonia', $datos['colonia'], PDO::PARAM_STR);
            $stmt->bindValue(':ciudad', $datos['ciudad'], PDO::PARAM_STR);
            $stmt->bindValue(':estado', $datos['estado'], PDO::PARAM_STR);
            $stmt->bindValue(':codigo_postal', $datos['codigo_postal'], PDO::PARAM_STR);
            $stmt->bindValue(':telefono', $datos['telefono'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $datos['email'], PDO::PARAM_STR);
            $stmt->bindValue(':sitio_web', $datos['sitio_web'], PDO::PARAM_STR);
            $stmt->bindValue(':logo_path', $datos['logo_path'], PDO::PARAM_STR);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error en actualizarConfiguracion(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crear configuración por defecto
     */
    private function crearConfiguracionPorDefecto() {
        try {
            $sql = "INSERT INTO {$this->tabla} 
                    (id, nombre_empresa, rfc, direccion, ciudad, estado, codigo_postal, telefono, email) 
                    VALUES 
                    (1, 'Tu Empresa S.A. de C.V.', 'XXXX-XXXXXX-XXX', 'Calle Ejemplo #123', 
                     'Ciudad', 'Estado', '00000', '(000) 000-0000', 'contacto@tuempresa.com')";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error en crearConfiguracionPorDefecto(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Validar datos de configuración
     * 
     * @param array $datos
     * @return array
     */
    public function validar($datos) {
        $errores = [];
        
        if (empty($datos['nombre_empresa'])) {
            $errores[] = "El nombre de la empresa es requerido";
        }
        
        if (empty($datos['rfc'])) {
            $errores[] = "El RFC es requerido";
        }
        
        if (empty($datos['direccion'])) {
            $errores[] = "La dirección es requerida";
        }
        
        if (empty($datos['ciudad'])) {
            $errores[] = "La ciudad es requerida";
        }
        
        if (empty($datos['estado'])) {
            $errores[] = "El estado es requerido";
        }
        
        if (empty($datos['codigo_postal'])) {
            $errores[] = "El código postal es requerido";
        }
        
        if (!empty($datos['email']) && !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no es válido";
        }
        
        return [
            'valido' => empty($errores),
            'errores' => $errores
        ];
    }
}
?>