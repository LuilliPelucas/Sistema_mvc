<?php
/**
 * Modelo Cotizacion
 * 
 * Maneja las operaciones de cotizaciones con transacciones
 * Incluye manejo de encabezado y partidas
 * 
 * @author Tu Nombre
 * @version 2.0
 */

class Cotizacion extends Model {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->tabla = 'cotizaciones';
        $this->primaryKey = 'cotizacionesid';
    }
    
    /**
     * Generar folio único para la cotización
     * 
     * @return string Folio generado
     */
    private function generarFolio() {
        try {
            $anioActual = date('Y');
            
            error_log("Generando folio para el año: " . $anioActual);
            
            // Obtener el último folio del año actual
            $sql = "SELECT folio FROM cotizaciones 
                    WHERE folio LIKE :patron 
                    ORDER BY cotizacionesid DESC 
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $patron = "COT-{$anioActual}-%";
            $stmt->bindParam(':patron', $patron, PDO::PARAM_STR);
            $stmt->execute();
            
            $ultimoFolio = $stmt->fetch();
            
            if ($ultimoFolio) {
                // Extraer el número del último folio
                // Formato: COT-2025-0001
                $partes = explode('-', $ultimoFolio['folio']);
                $ultimoNumero = isset($partes[2]) ? intval($partes[2]) : 0;
                $siguienteNumero = $ultimoNumero + 1;
                
                error_log("Último folio encontrado: " . $ultimoFolio['folio']);
                error_log("Siguiente número: " . $siguienteNumero);
            } else {
                // Primer folio del año
                $siguienteNumero = 1;
                error_log("Primer folio del año");
            }
            
            // Generar folio con formato: COT-2025-0001
            $nuevoFolio = sprintf('COT-%d-%04d', $anioActual, $siguienteNumero);
            
            error_log("✅ Folio generado: " . $nuevoFolio);
            
            return $nuevoFolio;
            
        } catch (PDOException $e) {
            error_log("❌ Error al generar folio: " . $e->getMessage());
            // Folio temporal único en caso de error
            return 'COT-' . date('Y') . '-' . time();
        }
    }
    
    /**
     * Crear cotización completa con partidas usando transacciones
     * 
     * @param array $datosEncabezado Datos del encabezado de la cotización
     * @param array $partidas Array de partidas
     * @return array Resultado con 'success' y 'cotizacionesid' o 'error'
     */
    public function crearCotizacionCompleta($datosEncabezado, $partidas) {
        try {
            error_log("=== Iniciando creación de cotización ===");
            
            // Iniciar transacción
            $this->db->beginTransaction();
            error_log("Transacción iniciada");
            
            // Generar folio si no viene o está vacío
            if (empty($datosEncabezado['folio'])) {
                $datosEncabezado['folio'] = $this->generarFolio();
                error_log("Folio generado manualmente: " . $datosEncabezado['folio']);
            } else {
                error_log("Usando folio proporcionado: " . $datosEncabezado['folio']);
            }
            
            // 1. Insertar el encabezado de la cotización
            $sqlEncabezado = "INSERT INTO cotizaciones 
                (clientesid, folio, fecha, condiciones, dias_vigencia, contacto, 
                 dias_entrega, subtotal, iva, total, status, observaciones) 
                VALUES 
                (:clientesid, :folio, :fecha, :condiciones, :dias_vigencia, :contacto, 
                 :dias_entrega, :subtotal, :iva, :total, :status, :observaciones)";
            
            $stmt = $this->db->prepare($sqlEncabezado);
            
            // Bind de parámetros del encabezado
            $stmt->bindValue(':clientesid', $datosEncabezado['clientesid'], PDO::PARAM_INT);
            $stmt->bindValue(':folio', $datosEncabezado['folio'], PDO::PARAM_STR);
            $stmt->bindValue(':fecha', $datosEncabezado['fecha'], PDO::PARAM_STR);
            $stmt->bindValue(':condiciones', $datosEncabezado['condiciones'], PDO::PARAM_STR);
            $stmt->bindValue(':dias_vigencia', $datosEncabezado['dias_vigencia'], PDO::PARAM_INT);
            $stmt->bindValue(':contacto', $datosEncabezado['contacto'], PDO::PARAM_STR);
            $stmt->bindValue(':dias_entrega', $datosEncabezado['dias_entrega'], PDO::PARAM_INT);
            $stmt->bindValue(':subtotal', $datosEncabezado['subtotal'], PDO::PARAM_STR);
            $stmt->bindValue(':iva', $datosEncabezado['iva'], PDO::PARAM_STR);
            $stmt->bindValue(':total', $datosEncabezado['total'], PDO::PARAM_STR);
            $stmt->bindValue(':status', $datosEncabezado['status'] ?? 'Pendiente', PDO::PARAM_STR);
            $stmt->bindValue(':observaciones', $datosEncabezado['observaciones'] ?? '', PDO::PARAM_STR);
            
            error_log("Ejecutando INSERT del encabezado...");
            $stmt->execute();
            
            // Obtener el ID de la cotización recién creada
            $cotizacionesid = $this->db->lastInsertId();
            
            error_log("✅ Cotización insertada - ID: " . $cotizacionesid . ", Folio: " . $datosEncabezado['folio']);
            
            // 2. Insertar las partidas
            $sqlPartida = "INSERT INTO partidas_cotizaciones 
                (cotizacionesid, partida_numero, codigoarticulo, descripcion, cantidad, 
                precio_unitario, precio_total, dias_entrega, unidad_medida, 
                porcentaje_iva, monto_iva, subtotal_partida, total_partida, observaciones_partida) 
                VALUES 
                (:cotizacionesid, :partida_numero, :codigoarticulo, :descripcion, :cantidad, 
                :precio_unitario, :precio_total, :dias_entrega, :unidad_medida, 
                :porcentaje_iva, :monto_iva, :subtotal_partida, :total_partida, :observaciones_partida)";

            
            $stmtPartida = $this->db->prepare($sqlPartida);
            
            error_log("Insertando " . count($partidas) . " partidas...");
            
            foreach ($partidas as $index => $partida) {
                $stmtPartida->bindValue(':cotizacionesid', $cotizacionesid, PDO::PARAM_INT);
                $stmtPartida->bindValue(':partida_numero', $index + 1, PDO::PARAM_INT);
                $stmtPartida->bindValue(':codigoarticulo', $partida['codigoarticulo'], PDO::PARAM_STR);
                $stmtPartida->bindValue(':descripcion', $partida['descripcion'], PDO::PARAM_STR);
                $stmtPartida->bindValue(':cantidad', $partida['cantidad'], PDO::PARAM_STR);
                $stmtPartida->bindValue(':precio_unitario', $partida['precio_unitario'], PDO::PARAM_STR);
                $stmtPartida->bindValue(':precio_total', $partida['precio_total'], PDO::PARAM_STR);
                $stmtPartida->bindValue(':dias_entrega', $partida['dias_entrega'], PDO::PARAM_INT);
                $stmtPartida->bindValue(':unidad_medida', $partida['unidad_medida'], PDO::PARAM_STR);
                $stmtPartida->bindValue(':porcentaje_iva', $partida['porcentaje_iva'], PDO::PARAM_STR);
                $stmtPartida->bindValue(':monto_iva', $partida['monto_iva'], PDO::PARAM_STR);
                $stmtPartida->bindValue(':subtotal_partida', $partida['subtotal_partida'], PDO::PARAM_STR);
                $stmtPartida->bindValue(':total_partida', $partida['total_partida'], PDO::PARAM_STR);
                $stmtPartida->bindValue(':observaciones_partida', $partida['observaciones_partida'] ?? '', PDO::PARAM_STR);
                
                $stmtPartida->execute();
                error_log("  Partida " . ($index + 1) . " insertada: " . $partida['descripcion']);
            }
            
            // Si todo fue exitoso, confirmar la transacción
            $this->db->commit();
            error_log("✅ Transacción confirmada exitosamente");
            
            return [
                'success' => true,
                'cotizacionesid' => $cotizacionesid,
                'folio' => $datosEncabezado['folio'],
                'mensaje' => 'Cotización creada exitosamente con folio: ' . $datosEncabezado['folio']
            ];
            
        } catch (PDOException $e) {
            // Si hubo un error, revertir todos los cambios
            $this->db->rollBack();
            
            error_log("❌ Error en crearCotizacionCompleta():");
            error_log("   Mensaje: " . $e->getMessage());
            error_log("   SQL State: " . $e->getCode());
            error_log("   Stack trace: " . $e->getTraceAsString());
            
            // Mensaje más amigable para errores comunes
            $mensajeError = $e->getMessage();
            
            if (strpos($mensajeError, 'Duplicate entry') !== false && strpos($mensajeError, 'folio') !== false) {
                $mensajeError = 'Error: El folio ya existe en la base de datos. Intente nuevamente.';
            }
            
            return [
                'success' => false,
                'error' => 'Error al crear la cotización: ' . $mensajeError
            ];
        }
    }
    
    /**
     * Obtener cotización con partidas
     * 
     * @param int $id ID de la cotización
     * @return array Datos de la cotización con partidas
     */
    public function getCotizacionConPartidas($id) {
        try {
            // Obtener encabezado de la cotización
            $sqlEncabezado = "SELECT c.*, cl.nombrecliente, cl.clavecliente 
                FROM cotizaciones c
                INNER JOIN clientes cl ON c.clientesid = cl.clientesid
                WHERE c.cotizacionesid = :id";
            
            $stmt = $this->db->prepare($sqlEncabezado);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $cotizacion = $stmt->fetch();
            
            if (!$cotizacion) {
                return false;
            }
            
            // Obtener partidas
            $sqlPartidas = "SELECT * FROM partidas_cotizaciones 
                WHERE cotizacionesid = :id 
                ORDER BY partida_numero ASC";
            
            $stmtPartidas = $this->db->prepare($sqlPartidas);
            $stmtPartidas->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtPartidas->execute();
            $partidas = $stmtPartidas->fetchAll();
            
            // Combinar encabezado con partidas
            $cotizacion['partidas'] = $partidas;
            
            return $cotizacion;
            
        } catch (PDOException $e) {
            error_log("Error en getCotizacionConPartidas(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener todas las cotizaciones con datos del cliente
     * 
     * @return array Lista de cotizaciones
     */
    public function getAllConCliente() {
        try {
            $sql = "SELECT c.*, cl.nombrecliente, cl.clavecliente,
                    (SELECT COUNT(*) FROM partidas_cotizaciones WHERE cotizacionesid = c.cotizacionesid) as total_partidas
                    FROM cotizaciones c
                    INNER JOIN clientes cl ON c.clientesid = cl.clientesid
                    ORDER BY c.cotizacionesid DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Error en getAllConCliente(): " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Actualizar status de una cotización
     * 
     * @param int $id ID de la cotización
     * @param string $nuevoStatus Nuevo status
     * @return bool True si se actualizó correctamente
     */
    public function actualizarStatus($id, $nuevoStatus) {
        try {
            $sql = "UPDATE cotizaciones SET status = :status WHERE cotizacionesid = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $nuevoStatus, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error en actualizarStatus(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Validar datos de cotización
     * 
     * @param array $datosEncabezado Datos del encabezado
     * @param array $partidas Array de partidas
     * @return array Resultado de validación
     */
    public function validar($datosEncabezado, $partidas) {
        $errores = [];
        
        // Validar encabezado
        if (empty($datosEncabezado['clientesid'])) {
            $errores[] = "El cliente es requerido";
        }
        
        if (empty($datosEncabezado['fecha'])) {
            $errores[] = "La fecha es requerida";
        }
        
        if (empty($datosEncabezado['condiciones'])) {
            $errores[] = "Las condiciones de pago son requeridas";
        }
        
        // Validar partidas
        if (empty($partidas) || count($partidas) == 0) {
            $errores[] = "Debe agregar al menos una partida";
        }
        
        foreach ($partidas as $index => $partida) {
            $num = $index + 1;
            
            if (empty($partida['codigoarticulo'])) {
                $errores[] = "El código del artículo es requerido en la partida $num";
            }
            
            if (empty($partida['descripcion'])) {
                $errores[] = "La descripción es requerida en la partida $num";
            }
            
            if (!isset($partida['cantidad']) || $partida['cantidad'] <= 0) {
                $errores[] = "La cantidad debe ser mayor a 0 en la partida $num";
            }
            
            if (!isset($partida['precio_unitario']) || $partida['precio_unitario'] < 0) {
                $errores[] = "El precio unitario no puede ser negativo en la partida $num";
            }
        }
        
        return [
            'valido' => empty($errores),
            'errores' => $errores
        ];
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
     * Obtener estadísticas de cotizaciones
     * 
     * @return array Estadísticas
     */
    public function getEstadisticas() {
        try {
            $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'Pendiente' THEN 1 ELSE 0 END) as pendientes,
                    SUM(CASE WHEN status = 'Aprobada' THEN 1 ELSE 0 END) as aprobadas,
                    SUM(CASE WHEN status = 'Rechazada' THEN 1 ELSE 0 END) as rechazadas,
                    SUM(total) as monto_total,
                    AVG(total) as promedio
                    FROM cotizaciones
                    WHERE YEAR(fecha) = YEAR(CURDATE())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Error en getEstadisticas(): " . $e->getMessage());
            return [];
        }
    }
}
?>