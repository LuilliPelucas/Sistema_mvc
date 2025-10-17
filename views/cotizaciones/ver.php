<?php
/**
 * Vista: Ver Cotización
 * 
 * Muestra el detalle completo de una cotización
 */

// Incluir header del dashboard
require_once VIEWS_PATH . 'layout/header_dashboard.php';
?>

<style>
.cotizacion-header {
    background: linear-gradient(135deg, #5c9ead, #7db8c7);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
}

.info-cotizacion {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.info-row {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #5c9ead;
    width: 200px;
}

.info-value {
    flex: 1;
    color: #2c3e50;
}

.partidas-table {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.totalizacion-box {
    background: linear-gradient(135deg, #5c9ead, #7db8c7);
    color: white;
    padding: 25px;
    border-radius: 15px;
    margin-top: 20px;
}

.total-line {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,0.3);
}

.total-line.final {
    border-top: 2px solid white;
    border-bottom: none;
    font-size: 1.5rem;
    font-weight: bold;
    margin-top: 10px;
}
</style>

<div class="card card-custom">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h3 class="mb-0"><i class="fas fa-file-invoice me-2"></i><?php echo $titulo; ?></h3>
            <div class="d-flex gap-2">
                <a href="<?php echo BASE_URL; ?>cotizaciones/imprimir/<?php echo $cotizacion['cotizacionesid']; ?>" 
                   target="_blank"
                   class="btn btn-imprimir">
                    <i class="fas fa-print me-2"></i>Imprimir PDF
                </a>
                <a href="<?php echo BASE_URL; ?>cotizaciones" class="btn btn-volver">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body p-4">
        
        <!-- Encabezado de la Cotización -->
        <div class="cotizacion-header">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="mb-1">Cotización: <?php echo htmlspecialchars($cotizacion['folio']); ?></h2>
                    <p class="mb-0">
                        <?php
                        $statusColors = [
                            'Pendiente' => 'warning',
                            'Aprobada' => 'success',
                            'Rechazada' => 'danger',
                            'Vencida' => 'secondary'
                        ];
                        $color = $statusColors[$cotizacion['status']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?php echo $color; ?>" style="font-size: 1rem; padding: 8px 20px;">
                            <?php echo $cotizacion['status']; ?>
                        </span>
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h3 class="mb-1">Total: $<?php echo number_format($cotizacion['total'], 2); ?></h3>
                    <p class="mb-0">Fecha: <?php echo date('d/m/Y', strtotime($cotizacion['fecha'])); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Información General -->
        <div class="row">
            <div class="col-md-6">
                <div class="info-cotizacion">
                    <h4 class="mb-3"><i class="fas fa-user me-2"></i>Información del Cliente</h4>
                    <div class="info-row">
                        <div class="info-label">Cliente:</div>
                        <div class="info-value"><?php echo htmlspecialchars($cotizacion['nombrecliente']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Clave:</div>
                        <div class="info-value"><?php echo htmlspecialchars($cotizacion['clavecliente']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Contacto:</div>
                        <div class="info-value"><?php echo htmlspecialchars($cotizacion['contacto'] ?: 'N/A'); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-cotizacion">
                    <h4 class="mb-3"><i class="fas fa-info-circle me-2"></i>Detalles de la Cotización</h4>
                    <div class="info-row">
                        <div class="info-label">Condiciones:</div>
                        <div class="info-value">
                            <span class="badge <?php echo $cotizacion['condiciones'] == 'Credito' ? 'bg-warning' : 'bg-success'; ?>">
                                <?php echo $cotizacion['condiciones']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Días de Vigencia:</div>
                        <div class="info-value"><?php echo $cotizacion['dias_vigencia']; ?> días</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Días de Entrega:</div>
                        <div class="info-value"><?php echo $cotizacion['dias_entrega']; ?> días</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Fecha de Creación:</div>
                        <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($cotizacion['fecha_creacion'])); ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Observaciones -->
        <?php if (!empty($cotizacion['observaciones'])): ?>
        <div class="row mt-3">
            <div class="col-12">
                <div class="info-cotizacion">
                    <h4 class="mb-3"><i class="fas fa-comment me-2"></i>Observaciones</h4>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($cotizacion['observaciones'])); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Partidas -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="mb-3"><i class="fas fa-list me-2"></i>Partidas de la Cotización</h4>
                
                <div class="partidas-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #5c9ead; color: white;">
                                <tr>
                                    <th>#</th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Unidad</th>
                                    <th class="text-end">Cantidad</th>
                                    <th class="text-end">Precio Unit.</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">% IVA</th>
                                    <th class="text-end">IVA</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Entrega</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($cotizacion['partidas'])): ?>
                                    <tr>
                                        <td colspan="11" class="text-center py-4">
                                            No hay partidas registradas
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($cotizacion['partidas'] as $partida): ?>
                                    <tr>
                                        <td><strong><?php echo $partida['partida_numero']; ?></strong></td>
                                        <td><?php echo htmlspecialchars($partida['codigoarticulo']); ?></td>
                                        <td><?php echo htmlspecialchars($partida['descripcion']); ?></td>
                                        <td><?php echo htmlspecialchars($partida['unidad_medida']); ?></td>
                                        <td class="text-end"><?php echo number_format($partida['cantidad'], 2); ?></td>
                                        <td class="text-end">$<?php echo number_format($partida['precio_unitario'], 2); ?></td>
                                        <td class="text-end">$<?php echo number_format($partida['precio_total'], 2); ?></td>
                                        <td class="text-center"><?php echo number_format($partida['porcentaje_iva'], 0); ?>%</td>
                                        <td class="text-end">$<?php echo number_format($partida['monto_iva'], 2); ?></td>
                                        <td class="text-end"><strong>$<?php echo number_format($partida['total_partida'], 2); ?></strong></td>
                                        <td class="text-center"><?php echo $partida['dias_entrega']; ?> días</td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Totalización -->
        <div class="row">
            <div class="col-md-6 offset-md-6">
                <div class="totalizacion-box">
                    <h4><i class="fas fa-calculator me-2"></i>Totales</h4>
                    <div class="total-line">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($cotizacion['subtotal'], 2); ?></span>
                    </div>
                    <div class="total-line">
                        <span>IVA:</span>
                        <span>$<?php echo number_format($cotizacion['iva'], 2); ?></span>
                    </div>
                    <div class="total-line final">
                        <span>TOTAL:</span>
                        <span>$<?php echo number_format($cotizacion['total'], 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botones de Acción -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?php echo BASE_URL; ?>cotizaciones/imprimir/<?php echo $cotizacion['cotizacionesid']; ?>" 
                       target="_blank"
                       class="btn btn-imprimir">
                        <i class="fas fa-print me-2"></i>Imprimir PDF
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>cotizaciones/enviarEmail/<?php echo $cotizacion['cotizacionesid']; ?>" 
                       class="btn btn-info">
                        <i class="fas fa-envelope me-2"></i>Enviar por Email
                    </a>
                    
                    <?php if ($cotizacion['status'] == 'Pendiente'): ?>
                    <button class="btn btn-success" onclick="cambiarStatus(<?php echo $cotizacion['cotizacionesid']; ?>, 'Aprobada')">
                        <i class="fas fa-check me-2"></i>Aprobar
                    </button>
                    
                    <button class="btn btn-danger" onclick="cambiarStatus(<?php echo $cotizacion['cotizacionesid']; ?>, 'Rechazada')">
                        <i class="fas fa-times me-2"></i>Rechazar
                    </button>
                    <?php endif; ?>
                    
                    <a href="<?php echo BASE_URL; ?>cotizaciones" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver a la Lista
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
function cambiarStatus(cotizacionId, nuevoStatus) {
    Swal.fire({
        title: '¿Cambiar status?',
        text: `¿Desea cambiar el status a "${nuevoStatus}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#5c9ead',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí implementarías la llamada AJAX para cambiar el status
            Swal.fire(
                'Actualizado',
                'El status ha sido actualizado',
                'success'
            ).then(() => {
                location.reload();
            });
        }
    });
}
</script>

<?php
// Incluir footer
require_once VIEWS_PATH . 'layout/footer.php';
?>
