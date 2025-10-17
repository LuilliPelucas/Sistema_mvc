<?php
/**
 * Vista: Editar Artículo de Inventario
 * Versión actualizada con precios
 */

// Incluir header del dashboard
require_once VIEWS_PATH . 'layout/header_dashboard.php';
?>
<div class="card card-custom">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="fas fa-edit me-2"></i><?php echo $titulo; ?></h3>
            <a href="<?php echo BASE_URL; ?>inventarios" class="btn btn-volver">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo BASE_URL; ?>inventarios/editar/<?php echo $inventario['inventariosid']; ?>" method="POST" id="formInventario">
            
            <!-- SECCIÓN: Información Básica -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-info-circle me-2"></i>Información Básica
                </h5>
                <div class="row">
                    <!-- Código Artículo -->
                    <div class="col-md-6 mb-3">
                        <label for="codigoarticulo" class="form-label">
                            Código Artículo <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="codigoarticulo" 
                               name="codigoarticulo" 
                               required 
                               maxlength="45"
                               value="<?php echo htmlspecialchars($inventario['codigoarticulo']); ?>">
                    </div>
                    
                    <!-- Unidad -->
                    <div class="col-md-6 mb-3">
                        <label for="unidad" class="form-label">Unidad <span class="text-danger">*</span></label>
                        <select class="form-control" id="unidad" name="unidad" required>
                            <option value="PZA" <?php echo $inventario['unidad'] == 'PZA' ? 'selected' : ''; ?>>PZA - Pieza</option>
                            <option value="KG" <?php echo $inventario['unidad'] == 'KG' ? 'selected' : ''; ?>>KG - Kilogramo</option>
                            <option value="LT" <?php echo $inventario['unidad'] == 'LT' ? 'selected' : ''; ?>>LT - Litro</option>
                            <option value="MT" <?php echo $inventario['unidad'] == 'MT' ? 'selected' : ''; ?>>MT - Metro</option>
                            <option value="CJ" <?php echo $inventario['unidad'] == 'CJ' ? 'selected' : ''; ?>>CJ - Caja</option>
                            <option value="PAQ" <?php echo $inventario['unidad'] == 'PAQ' ? 'selected' : ''; ?>>PAQ - Paquete</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Descripción -->
                    <div class="col-md-12 mb-3">
                        <label for="descripcion" class="form-label">
                            Descripción <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="descripcion" 
                               name="descripcion" 
                               required 
                               maxlength="245"
                               value="<?php echo htmlspecialchars($inventario['descripcion']); ?>">
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN: Precios -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-dollar-sign me-2"></i>Información de Precios
                </h5>
                <div class="row">
                    <!-- Precio Costo -->
                    <div class="col-md-4 mb-3">
                        <label for="precio_costo" class="form-label">
                            Precio Costo <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" 
                                   class="form-control" 
                                   id="precio_costo" 
                                   name="precio_costo" 
                                   required 
                                   step="0.01"
                                   min="0"
                                   value="<?php echo isset($inventario['precio_costo']) ? $inventario['precio_costo'] : '0.00'; ?>">
                        </div>
                    </div>
                    
                    <!-- Precio Venta -->
                    <div class="col-md-4 mb-3">
                        <label for="precio_venta" class="form-label">
                            Precio Venta <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" 
                                   class="form-control" 
                                   id="precio_venta" 
                                   name="precio_venta" 
                                   required 
                                   step="0.01"
                                   min="0"
                                   value="<?php echo isset($inventario['precio_venta']) ? $inventario['precio_venta'] : '0.00'; ?>">
                        </div>
                    </div>
                    
                    <!-- Moneda -->
                    <div class="col-md-4 mb-3">
                        <label for="moneda" class="form-label">
                            Moneda <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" id="moneda" name="moneda" required>
                            <option value="PESOS" <?php echo (isset($inventario['moneda']) && $inventario['moneda'] == 'PESOS') ? 'selected' : ''; ?>>PESOS</option>
                            <option value="DOLARES" <?php echo (isset($inventario['moneda']) && $inventario['moneda'] == 'DOLARES') ? 'selected' : ''; ?>>DÓLARES</option>
                        </select>
                    </div>
                </div>
                
                <!-- Margen de utilidad -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info" id="margenInfo">
                            <i class="fas fa-chart-line me-2"></i>
                            <strong>Margen de Utilidad:</strong> <span id="margenCalculado"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN: Existencias -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-boxes me-2"></i>Control de Existencias
                </h5>
                <div class="row">
                    <!-- Existencia -->
                    <div class="col-md-4 mb-3">
                        <label for="existencia" class="form-label">
                            Existencia <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control" 
                               id="existencia" 
                               name="existencia" 
                               required 
                               step="0.01"
                               value="<?php echo $inventario['existencia']; ?>">
                    </div>
                    
                    <!-- Mínimo -->
                    <div class="col-md-4 mb-3">
                        <label for="minimo" class="form-label">
                            Mínimo <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control" 
                               id="minimo" 
                               name="minimo" 
                               required 
                               step="0.01"
                               value="<?php echo $inventario['minimo']; ?>">
                    </div>
                    
                    <!-- Máximo -->
                    <div class="col-md-4 mb-3">
                        <label for="maximo" class="form-label">
                            Máximo <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control" 
                               id="maximo" 
                               name="maximo" 
                               required 
                               step="0.01"
                               value="<?php echo $inventario['maximo']; ?>">
                    </div>
                </div>
            </div>
            
            <!-- Estado -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="activo" 
                               name="activo" 
                               value="1" 
                               <?php echo $inventario['activo'] == 1 ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="activo">
                            <strong>Artículo Activo</strong>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Botones -->
            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn btn-guardar">
                        <i class="fas fa-save me-2"></i>Actualizar Artículo
                    </button>
                    <a href="<?php echo BASE_URL; ?>inventarios" class="btn btn-cancelar">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </div>
            
        </form>
    </div>
</div>

<script>
// Calcular margen de utilidad en tiempo real
$(document).ready(function() {
    function calcularMargen() {
        const precioCosto = parseFloat($('#precio_costo').val()) || 0;
        const precioVenta = parseFloat($('#precio_venta').val()) || 0;
        
        if (precioCosto > 0) {
            const margen = ((precioVenta - precioCosto) / precioCosto) * 100;
            $('#margenCalculado').text(margen.toFixed(2) + '%');
            
            // Cambiar color según el margen
            if (margen < 0) {
                $('#margenCalculado').css('color', '#dc3545'); // Rojo - Pérdida
            } else if (margen < 20) {
                $('#margenCalculado').css('color', '#ffc107'); // Amarillo - Bajo
            } else {
                $('#margenCalculado').css('color', '#28a745'); // Verde - Bueno
            }
        } else {
            $('#margenCalculado').text('N/A');
            $('#margenCalculado').css('color', '#6c757d');
        }
    }
    
    // Calcular al cargar
    calcularMargen();
    
    // Calcular al cambiar valores
    $('#precio_costo, #precio_venta').on('input', calcularMargen);
});
</script>

<?php
// Incluir footer
require_once VIEWS_PATH . 'layout/footer.php';
?>