<?php
/**
 * Vista: Editar Proveedor
 * 
 * Formulario para editar un proveedor existente
 */

// Incluir header
require_once VIEWS_PATH . 'layout/header.php';
?>

<div class="card card-custom">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="fas fa-user-edit me-2"></i><?php echo $titulo; ?></h3>
            <a href="<?php echo BASE_URL; ?>proveedores" class="btn btn-volver">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo BASE_URL; ?>proveedores/editar/<?php echo $proveedor['proveedoresid']; ?>" method="POST" id="formProveedor">
            
            <div class="row">
                <!-- Clave Proveedor -->
                <div class="col-md-6 mb-3">
                    <label for="claveproveedor" class="form-label">
                        Clave Proveedor <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="claveproveedor" 
                           name="claveproveedor" 
                           required 
                           maxlength="45"
                           value="<?php echo htmlspecialchars($proveedor['claveproveedor']); ?>">
                </div>
                
                <!-- Nombre Proveedor -->
                <div class="col-md-6 mb-3">
                    <label for="nombreproveedor" class="form-label">
                        Nombre Proveedor <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="nombreproveedor" 
                           name="nombreproveedor" 
                           required 
                           maxlength="245"
                           value="<?php echo htmlspecialchars($proveedor['nombreproveedor']); ?>">
                </div>
            </div>
            
            <div class="row">
                <!-- Dirección -->
                <div class="col-md-6 mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" 
                           class="form-control" 
                           id="direccion" 
                           name="direccion" 
                           maxlength="145"
                           value="<?php echo htmlspecialchars($proveedor['direccion']); ?>">
                </div>
                
                <!-- Número Exterior -->
                <div class="col-md-3 mb-3">
                    <label for="exterior" class="form-label">No. Exterior</label>
                    <input type="text" 
                           class="form-control" 
                           id="exterior" 
                           name="exterior" 
                           maxlength="45"
                           value="<?php echo htmlspecialchars($proveedor['exterior']); ?>">
                </div>
                
                <!-- Número Interior -->
                <div class="col-md-3 mb-3">
                    <label for="interior" class="form-label">No. Interior</label>
                    <input type="text" 
                           class="form-control" 
                           id="interior" 
                           name="interior" 
                           maxlength="45"
                           value="<?php echo htmlspecialchars($proveedor['interior']); ?>">
                </div>
            </div>
            
            <div class="row">
                <!-- Colonia -->
                <div class="col-md-6 mb-3">
                    <label for="colonia" class="form-label">Colonia</label>
                    <input type="text" 
                           class="form-control" 
                           id="colonia" 
                           name="colonia" 
                           maxlength="145"
                           value="<?php echo htmlspecialchars($proveedor['colonia']); ?>">
                </div>
                
                <!-- Ciudad -->
                <div class="col-md-3 mb-3">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <input type="text" 
                           class="form-control" 
                           id="ciudad" 
                           name="ciudad" 
                           maxlength="45"
                           value="<?php echo htmlspecialchars($proveedor['ciudad']); ?>">
                </div>
                
                <!-- Estado -->
                <div class="col-md-3 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <input type="text" 
                           class="form-control" 
                           id="estado" 
                           name="estado" 
                           maxlength="45"
                           value="<?php echo htmlspecialchars($proveedor['estado']); ?>">
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn btn-guardar">
                        <i class="fas fa-save me-2"></i>Actualizar Proveedor
                    </button>
                    <a href="<?php echo BASE_URL; ?>proveedores" class="btn btn-cancelar">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </div>
            
        </form>
    </div>
</div>

<?php
// Incluir footer
require_once VIEWS_PATH . 'layout/footer.php';
?>