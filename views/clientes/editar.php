<?php
/**
 * Vista: Editar Cliente
 * 
 * Formulario para editar un cliente existente
 */

// Incluir header del dashboard
require_once VIEWS_PATH . 'layout/header_dashboard.php';
?>

<div class="card card-custom">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="fas fa-user-edit me-2"></i><?php echo $titulo; ?></h3>
            <a href="<?php echo BASE_URL; ?>clientes" class="btn btn-volver">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo BASE_URL; ?>clientes/editar/<?php echo $cliente['clientesid']; ?>" method="POST" id="formCliente">
            
            <div class="row">
                <!-- Clave Cliente -->
                <div class="col-md-6 mb-3">
                    <label for="clavecliente" class="form-label">
                        Clave Cliente <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="clavecliente" 
                           name="clavecliente" 
                           required 
                           maxlength="45"
                           value="<?php echo htmlspecialchars($cliente['clavecliente']); ?>">
                </div>
                
                <!-- Nombre Cliente -->
                <div class="col-md-6 mb-3">
                    <label for="nombrecliente" class="form-label">
                        Nombre Cliente <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="nombrecliente" 
                           name="nombrecliente" 
                           required 
                           maxlength="245"
                           value="<?php echo htmlspecialchars($cliente['nombrecliente']); ?>">
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
                           value="<?php echo htmlspecialchars($cliente['direccion']); ?>">
                </div>
                
                <!-- Número Exterior -->
                <div class="col-md-3 mb-3">
                    <label for="exterior" class="form-label">No. Exterior</label>
                    <input type="text" 
                           class="form-control" 
                           id="exterior" 
                           name="exterior" 
                           maxlength="45"
                           value="<?php echo htmlspecialchars($cliente['exterior']); ?>">
                </div>
                
                <!-- Número Interior -->
                <div class="col-md-3 mb-3">
                    <label for="interior" class="form-label">No. Interior</label>
                    <input type="text" 
                           class="form-control" 
                           id="interior" 
                           name="interior" 
                           maxlength="45"
                           value="<?php echo htmlspecialchars($cliente['interior']); ?>">
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
                           value="<?php echo htmlspecialchars($cliente['colonia']); ?>">
                </div>
                
                <!-- Ciudad -->
                <div class="col-md-3 mb-3">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <input type="text" 
                           class="form-control" 
                           id="ciudad" 
                           name="ciudad" 
                           maxlength="45"
                           value="<?php echo htmlspecialchars($cliente['ciudad']); ?>">
                </div>
                
                <!-- Estado -->
                <div class="col-md-3 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <input type="text" 
                           class="form-control" 
                           id="estado" 
                           name="estado" 
                           maxlength="45"
                           value="<?php echo htmlspecialchars($cliente['estado']); ?>">
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn btn-guardar">
                        <i class="fas fa-save me-2"></i>Actualizar Cliente
                    </button>
                    <a href="<?php echo BASE_URL; ?>clientes" class="btn btn-cancelar">
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