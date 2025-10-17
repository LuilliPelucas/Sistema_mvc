<?php
/**
 * Vista: Crear Cotización
 * Versión profesional con DataTable para partidas
 */

// Incluir header del dashboard
require_once VIEWS_PATH . 'layout/header_dashboard.php';
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
.card-custom {
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #5c9ead, #7db8c7);
    color: white;
    border-radius: 15px 15px 0 0 !important;
    padding: 25px;
    border: none;
}

.section-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    border-left: 4px solid #5c9ead;
}

.section-header h4 {
    margin: 0;
    color: #2c3e50;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 10px 15px;
}

.form-control:focus, .form-select:focus {
    border-color: #5c9ead;
    box-shadow: 0 0 0 0.2rem rgba(92, 158, 173, 0.25);
}

.btn-agregar-partida {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-agregar-partida:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-guardar {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.1rem;
}

.btn-cancelar {
    background: #6c757d;
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 8px;
    font-weight: 600;
}

.totalizacion-box {
    background: linear-gradient(135deg, #5c9ead, #7db8c7);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-top: 30px;
    box-shadow: 0 4px 15px rgba(92, 158, 173, 0.3);
}

.totalizacion-box h4 {
    color: white;
    margin-bottom: 25px;
    font-weight: 600;
}

.total-line {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid rgba(255,255,255,0.3);
    font-size: 1.1rem;
}

.total-line.final {
    border-top: 3px solid white;
    border-bottom: none;
    font-size: 1.6rem;
    font-weight: bold;
    margin-top: 15px;
    padding-top: 20px;
}

/* Estilos para el modal de agregar partida */
.modal-content {
    border-radius: 15px;
    border: none;
}

.modal-header {
    background: linear-gradient(135deg, #5c9ead, #7db8c7);
    color: white;
    border-radius: 15px 15px 0 0;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

/* DataTable personalizado */
#tablaPartidas_wrapper {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

table.dataTable tbody tr {
    transition: all 0.3s;
}

table.dataTable tbody tr:hover {
    background-color: #e3f2fd !important;
}

.badge-partida {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
}

.btn-accion {
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    transition: all 0.3s;
}

.btn-accion:hover {
    transform: scale(1.1);
}

.partidas-count {
    background: white;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.partidas-count h5 {
    margin: 0;
    color: #5c9ead;
    font-weight: 600;
}
</style>

<div class="card card-custom">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1"><i class="fas fa-file-invoice me-2"></i><?php echo $titulo; ?></h3>
                <p class="mb-0 opacity-75">Complete la información de la cotización</p>
            </div>
            <a href="<?php echo BASE_URL; ?>cotizaciones" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo BASE_URL; ?>cotizaciones/crear" method="POST" id="formCotizacion">
            
            <!-- ENCABEZADO DE LA COTIZACIÓN -->
            <div class="section-header">
                <h4><i class="fas fa-info-circle me-2"></i>Datos Generales</h4>
            </div>
            
            <div class="row mb-4">
                <!-- Cliente -->
                <div class="col-md-6 mb-3">
                    <label for="clientesid" class="form-label fw-bold">
                        Cliente <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="clientesid" name="clientesid" required>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?php echo $cliente['clientesid']; ?>">
                                <?php echo htmlspecialchars($cliente['clavecliente'] . ' - ' . $cliente['nombrecliente']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Fecha -->
                <div class="col-md-3 mb-3">
                    <label for="fecha" class="form-label fw-bold">
                        Fecha <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control" id="fecha" name="fecha" 
                           value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <!-- Condiciones -->
                <div class="col-md-3 mb-3">
                    <label for="condiciones" class="form-label fw-bold">
                        Condiciones <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="condiciones" name="condiciones" required>
                        <option value="Contado">Contado</option>
                        <option value="Credito">Crédito</option>
                    </select>
                </div>
                
                <!-- Días de Vigencia -->
                <div class="col-md-3 mb-3">
                    <label for="dias_vigencia" class="form-label fw-bold">Días de Vigencia</label>
                    <select class="form-select" id="dias_vigencia" name="dias_vigencia">
                        <option value="1">1 día</option>
                        <option value="3">3 días</option>
                        <option value="7" selected>7 días</option>
                        <option value="15">15 días</option>
                        <option value="30">30 días</option>
                        <option value="60">60 días</option>
                    </select>
                </div>
                
                <!-- Contacto -->
                <div class="col-md-6 mb-3">
                    <label for="contacto" class="form-label fw-bold">Contacto</label>
                    <input type="text" class="form-control" id="contacto" name="contacto" 
                           placeholder="Nombre del contacto">
                </div>
                
                <!-- Días de Entrega -->
                <div class="col-md-3 mb-3">
                    <label for="dias_entrega" class="form-label fw-bold">Días de Entrega</label>
                    <select class="form-select" id="dias_entrega" name="dias_entrega">
                        <option value="1">1 día</option>
                        <option value="3">3 días</option>
                        <option value="7" selected>7 días</option>
                        <option value="15">15 días</option>
                        <option value="30">30 días</option>
                        <option value="60">60 días</option>
                    </select>
                </div>
            </div>
            
            <!-- PARTIDAS -->
            <div class="section-header mt-5">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-list me-2"></i>Partidas de la Cotización</h4>
                    <button type="button" class="btn btn-agregar-partida" data-bs-toggle="modal" data-bs-target="#modalAgregarPartida">
                        <i class="fas fa-plus me-2"></i>Agregar Partida
                    </button>
                </div>
            </div>
            
            <!-- Contador de partidas -->
            <div class="partidas-count">
                <h5>Total de Partidas: <span id="contadorPartidas" class="text-primary">0</span></h5>
            </div>
            
            <!-- Tabla de partidas -->
            <div class="table-responsive">
                <table id="tablaPartidas" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Subtotal</th>
                            <th>% IVA</th>
                            <th>IVA</th>
                            <th>Total</th>
                            <th>Entrega</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Las partidas se agregan dinámicamente -->
                    </tbody>
                </table>
            </div>
            
            <!-- TOTALIZACIÓN -->
            <div class="row">
                <div class="col-md-5 offset-md-7">
                    <div class="totalizacion-box">
                        <h4><i class="fas fa-calculator me-2"></i>Totales</h4>
                        <div class="total-line">
                            <span>Subtotal:</span>
                            <span id="displaySubtotal">$0.00</span>
                        </div>
                        <div class="total-line">
                            <span>IVA:</span>
                            <span id="displayIVA">$0.00</span>
                        </div>
                        <div class="total-line final">
                            <span>TOTAL:</span>
                            <span id="displayTotal">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Observaciones -->
            <div class="row mt-4">
                <div class="col-12">
                    <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" 
                              rows="3" placeholder="Observaciones adicionales (opcional)"></textarea>
                </div>
            </div>
            
            <!-- Campos ocultos -->
            <input type="hidden" name="subtotal" id="subtotal">
            <input type="hidden" name="iva" id="iva">
            <input type="hidden" name="total" id="total">
            <input type="hidden" name="partidas_json" id="partidas_json">
            
            <!-- Botones -->
            <div class="row mt-5">
                <div class="col-12 d-flex gap-3">
                    <button type="submit" class="btn btn-guardar">
                        <i class="fas fa-save me-2"></i>Guardar Cotización
                    </button>
                    <a href="<?php echo BASE_URL; ?>cotizaciones" class="btn btn-cancelar">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </div>
            
        </form>
    </div>
</div>

<!-- Modal Agregar/Editar Partida -->
<div class="modal fade" id="modalAgregarPartida" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    <span id="tituloModal">Agregar Partida</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formPartida">
                    <input type="hidden" id="partidaIndex" value="-1">
                    
                    <div class="row">
                        <!-- Código Artículo -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Código Artículo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="codigoArticulo" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="buscarArticulo()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Descripción -->
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">Descripción <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="descripcionArticulo" required>
                        </div>
                        
                        <!-- Cantidad -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Cantidad <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="cantidadArticulo" 
                                   step="0.01" min="0.01" value="1" required>
                        </div>
                        
                        <!-- Unidad -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Unidad</label>
                            <select class="form-select" id="unidadArticulo">
                                <option value="PZA">PZA</option>
                                <option value="KG">KG</option>
                                <option value="LT">LT</option>
                                <option value="MT">MT</option>
                                <option value="CJ">CJ</option>
                            </select>
                        </div>
                        
                        <!-- Precio Unitario -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Precio Unit. <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="precioUnitario" 
                                   step="0.01" min="0" value="0" required>
                        </div>
                        
                        <!-- % IVA -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">% IVA</label>
                            <select class="form-select" id="porcentajeIVA">
                                <option value="0">0%</option>
                                <option value="8">8%</option>
                                <option value="16" selected>16%</option>
                            </select>
                        </div>
                        
                        <!-- Días de Entrega -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Días de Entrega</label>
                            <select class="form-select" id="diasEntregaArticulo">
                                <option value="1">1 día</option>
                                <option value="3">3 días</option>
                                <option value="7" selected>7 días</option>
                                <option value="15">15 días</option>
                                <option value="30">30 días</option>
                                <option value="60">60 días</option>
                            </select>
                        </div>
                        <!-- Observaciones de la partida -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Observaciones de la Partida</label>
                            <textarea class="form-control" 
                                    id="observacionesPartida" 
                                    rows="2" 
                                    placeholder="Observaciones específicas de esta partida (opcional)"></textarea>
                            <small class="text-muted">Aparecerá debajo de la partida en el documento impreso</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="guardarPartida()">
                    <i class="fas fa-check me-2"></i>Agregar Partida
                </button>
            </div>
        </div>
    </div>
</div>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- JavaScript de Cotizaciones -->
<script src="<?php echo BASE_URL; ?>public/js/cotizaciones_datatable.js"></script>

<?php
// Incluir footer del dashboard
require_once VIEWS_PATH . 'layout/footer.php';
?>