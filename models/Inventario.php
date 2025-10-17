<?php
/**
 * Modelo Inventario
 * Versión actualizada con precios
 */

class Inventario extends Model {
    
    public function __construct() {
        parent::__construct();
        
        // Ajusta estos valores según tu base de datos
        $this->tabla = 'inventarios';
        $this->primaryKey = 'inventariosid';
        
        error_log("Modelo Inventario inicializado - Tabla: " . $this->tabla);
    }
    
    /**
     * Buscar artículos por código o descripción
     * Incluye información de precios
     * 
     * @param string $termino Término de búsqueda
     * @return array Lista de artículos encontrados
     */
    public function buscar($termino) {
        try {
            error_log("📦 Inventario->buscar() llamado");
            error_log("   Término: '" . $termino . "'");
            
            // Primero, intentar búsqueda exacta por código
            $sql = "SELECT 
                        inventarioid,
                        codigoarticulo,
                        descripcion,
                        unidad,
                        existencia,
                        precio_costo,
                        precio_venta,
                        moneda,
                        margen_utilidad,
                        status
                    FROM {$this->tabla} 
                    WHERE codigoarticulo = :codigo_exacto
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':codigo_exacto', $termino, PDO::PARAM_STR);
            $stmt->execute();
            
            $resultadoExacto = $stmt->fetch();
            
            if ($resultadoExacto) {
                error_log("✅ Encontrado con búsqueda exacta");
                error_log("   Precio Venta: $" . $resultadoExacto['precio_venta']);
                error_log("   Moneda: " . $resultadoExacto['moneda']);
                return [$resultadoExacto];
            }
            
            error_log("   No encontrado con búsqueda exacta, intentando LIKE...");
            
            // Si no se encuentra, intentar búsqueda con LIKE
            $sql = "SELECT 
                        inventarioid,
                        codigoarticulo,
                        descripcion,
                        unidad,
                        existencia,
                        precio_costo,
                        precio_venta,
                        moneda,
                        margen_utilidad,
                        status
                    FROM {$this->tabla} 
                    WHERE codigoarticulo LIKE :termino 
                    OR descripcion LIKE :termino 
                    ORDER BY codigoarticulo ASC
                    LIMIT 10";
            
            $stmt = $this->db->prepare($sql);
            $terminoBusqueda = '%' . $termino . '%';
            $stmt->bindParam(':termino', $terminoBusqueda, PDO::PARAM_STR);
            $stmt->execute();
            
            $resultados = $stmt->fetchAll();
            error_log("   Resultados LIKE: " . count($resultados));
            
            if (!empty($resultados)) {
                error_log("✅ Artículos encontrados con LIKE: " . count($resultados));
                foreach ($resultados as $index => $art) {
                    error_log("   [$index] " . $art['codigoarticulo'] . " - " . $art['descripcion'] . " - $" . $art['precio_venta']);
                }
            } else {
                error_log("❌ No se encontraron artículos");
            }
            
            return $resultados;
            
        } catch (PDOException $e) {
            error_log("❌ ERROR en Inventario->buscar():");
            error_log("   Mensaje: " . $e->getMessage());
            error_log("   SQL State: " . $e->getCode());
            return [];
        }
    }
    
    /**
     * Buscar artículo por código exacto
     * 
     * @param string $codigo Código del artículo
     * @return array Artículo encontrado
     */
    public function buscarPorCodigo($codigo) {
        try {
            $sql = "SELECT * FROM {$this->tabla} 
                    WHERE codigoarticulo = :codigo 
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmt->execute();
            
            $resultado = $stmt->fetch();
            
            if ($resultado) {
                error_log("✅ Artículo encontrado: " . $resultado['descripcion']);
                return [$resultado];
            }
            
            return [];
            
        } catch (PDOException $e) {
            error_log("❌ Error en buscarPorCodigo(): " . $e->getMessage());
            return [];
        }
    }
    /**
     * Obtiene artículos con existencia baja (por debajo del mínimo)
     * 
     * @return array Array de artículos con existencia baja
     */
    public function getExistenciaBaja() {
        try {
            $sql = "SELECT * FROM {$this->tabla} 
                    WHERE existencia < minimo 
                    AND activo = 1
                    ORDER BY existencia ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en getExistenciaBaja(): " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener artículos activos
     * 
     * @return array Lista de artículos activos
     */
    public function getActivos() {
        try {
            $sql = "SELECT * FROM {$this->tabla} 
                    WHERE status = 'Activo' 
                    OR status IS NULL 
                    ORDER BY descripcion ASC 
                    LIMIT 500";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll();
            
            if (!empty($resultados)) {
                error_log("✅ Artículos activos: " . count($resultados));
                return $resultados;
            }
            
            // Si no hay resultados, intentar sin filtro
            $sql = "SELECT * FROM {$this->tabla} ORDER BY descripcion ASC LIMIT 500";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("❌ Error en getActivos(): " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Crear o actualizar artículo
     * 
     * @param array $datos Datos del artículo
     * @return int|bool ID del artículo o false
     */
    public function guardar($datos) {
        try {
            // Si viene el ID, es actualización
            if (!empty($datos['inventarioid'])) {
                return $this->actualizar($datos['inventarioid'], $datos);
            }
            
            // Si no, es inserción
            $sql = "INSERT INTO {$this->tabla} 
                    (codigoarticulo, descripcion, unidad, existencia, 
                     precio_costo, precio_venta, moneda, margen_utilidad, status)
                    VALUES 
                    (:codigoarticulo, :descripcion, :unidad, :existencia,
                     :precio_costo, :precio_venta, :moneda, :margen_utilidad, :status)";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':codigoarticulo', $datos['codigoarticulo'], PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
            $stmt->bindValue(':unidad', $datos['unidad'] ?? 'PZA', PDO::PARAM_STR);
            $stmt->bindValue(':existencia', $datos['existencia'] ?? 0, PDO::PARAM_STR);
            $stmt->bindValue(':precio_costo', $datos['precio_costo'] ?? 0, PDO::PARAM_STR);
            $stmt->bindValue(':precio_venta', $datos['precio_venta'] ?? 0, PDO::PARAM_STR);
            $stmt->bindValue(':moneda', $datos['moneda'] ?? 'PESOS', PDO::PARAM_STR);
            $stmt->bindValue(':margen_utilidad', $datos['margen_utilidad'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':status', $datos['status'] ?? 'Activo', PDO::PARAM_STR);
            
            $stmt->execute();
            
            return $this->db->lastInsertId();
            
        } catch (PDOException $e) {
            error_log("❌ Error en guardar(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar artículo
     * 
     * @param int $id ID del artículo
     * @param array $datos Datos a actualizar
     * @return bool
     */
    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE {$this->tabla} SET
                    codigoarticulo = :codigoarticulo,
                    descripcion = :descripcion,
                    unidad = :unidad,
                    existencia = :existencia,
                    precio_costo = :precio_costo,
                    precio_venta = :precio_venta,
                    moneda = :moneda,
                    margen_utilidad = :margen_utilidad,
                    status = :status
                    WHERE inventarioid = :id";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':codigoarticulo', $datos['codigoarticulo'], PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
            $stmt->bindValue(':unidad', $datos['unidad'], PDO::PARAM_STR);
            $stmt->bindValue(':existencia', $datos['existencia'], PDO::PARAM_STR);
            $stmt->bindValue(':precio_costo', $datos['precio_costo'], PDO::PARAM_STR);
            $stmt->bindValue(':precio_venta', $datos['precio_venta'], PDO::PARAM_STR);
            $stmt->bindValue(':moneda', $datos['moneda'], PDO::PARAM_STR);
            $stmt->bindValue(':margen_utilidad', $datos['margen_utilidad'], PDO::PARAM_STR);
            $stmt->bindValue(':status', $datos['status'], PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("❌ Error en actualizar(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Valida si un código de artículo ya existe
     * 
     * @param string $codigo Código a validar
     * @param int $excluirId ID a excluir de la validación
     * @return bool True si existe, false si no existe
     */
    public function codigoExiste($codigo, $excluirId = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} 
                    WHERE codigoarticulo = :codigo";
            
            if ($excluirId !== null) {
                $sql .= " AND {$this->primaryKey} != :id";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            
            if ($excluirId !== null) {
                $stmt->bindParam(':id', $excluirId, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $result = $stmt->fetch();
            
            return $result['total'] > 0;
        } catch (PDOException $e) {
            error_log("Error en codigoExiste(): " . $e->getMessage());
            return false;
        }
    }

    
    /**
     * Calcular margen de utilidad
     * 
     * @param float $precioCosto
     * @param float $precioVenta
     * @return float Porcentaje de utilidad
     */
    public function calcularMargen($precioCosto, $precioVenta) {
        if ($precioCosto <= 0) {
            return 0;
        }
        
        return (($precioVenta - $precioCosto) / $precioCosto) * 100;
    }
    
    /**
     * Validar datos del artículo
     * 
     * @param array $datos
     * @return array
     */
    public function validar($datos) {
        $errores = [];
        
        if (empty($datos['codigoarticulo'])) {
            $errores[] = "El código del artículo es requerido";
        }
        
        if (empty($datos['descripcion'])) {
            $errores[] = "La descripción es requerida";
        }
        
        if (isset($datos['precio_costo']) && $datos['precio_costo'] < 0) {
            $errores[] = "El precio de costo no puede ser negativo";
        }
        
        if (isset($datos['precio_venta']) && $datos['precio_venta'] < 0) {
            $errores[] = "El precio de venta no puede ser negativo";
        }
        
        if (isset($datos['existencia']) && $datos['existencia'] < 0) {
            $errores[] = "La existencia no puede ser negativa";
        }
        
        return [
            'valido' => empty($errores),
            'errores' => $errores
        ];
    }
}
?>