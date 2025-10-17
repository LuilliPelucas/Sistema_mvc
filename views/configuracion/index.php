<?php
/**
 * Vista: Configuración de Empresa
 */

// Incluir header del dashboard
require_once VIEWS_PATH . 'layout/header_dashboard.php';
?>

<div class="card card-custom">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1"><i class="fas fa-building me-2"></i><?php echo $titulo; ?></h3>
                <p class="mb-0 opacity-75">Configure los datos de su empresa</p>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo BASE_URL; ?>configuracion" method="POST" id="formConfiguracion">
            
            <!-- SECCIÓN: Información General -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-info-circle me-2"></i>Información General
                </h5>
                <div class="row">
                    <!-- Nombre de la Empresa -->
                    <div class="col-md-8 mb-3">
                        <label for="nombre_empresa" class="form-label fw-bold">
                            Nombre de la Empresa <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="nombre_empresa" 
                               name="nombre_empresa" 
                               required 
                               maxlength="255"
                               value="<?php echo htmlspecialchars($configuracion['nombre_empresa']); ?>"
                               placeholder="Ej: Mi Empresa S.A. de C.V.">
                    </div>
                    
                    <!-- RFC -->
                    <div class="col-md-4 mb-3">
                        <label for="rfc" class="form-label fw-bold">
                            RFC <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="rfc" 
                               name="rfc" 
                               required 
                               maxlength="20"
                               value="<?php echo htmlspecialchars($configuracion['rfc']); ?>"
                               placeholder="XXXX-XXXXXX-XXX"
                               style="text-transform: uppercase;">
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN: Dirección -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-map-marker-alt me-2"></i>Dirección
                </h5>
                <div class="row">
                    <!-- Dirección -->
                    <div class="col-md-8 mb-3">
                        <label for="direccion" class="form-label fw-bold">
                            Dirección <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="direccion" 
                               name="direccion" 
                               required 
                               maxlength="255"
                               value="<?php echo htmlspecialchars($configuracion['direccion']); ?>"
                               placeholder="Calle y número">
                    </div>
                    
                    <!-- Colonia -->
                    <div class="col-md-4 mb-3">
                        <label for="colonia" class="form-label fw-bold">Colonia</label>
                        <input type="text" 
                               class="form-control" 
                               id="colonia" 
                               name="colonia" 
                               maxlength="100"
                               value="<?php echo htmlspecialchars($configuracion['colonia']); ?>"
                               placeholder="Colonia">
                    </div>
                    
                    <!-- Ciudad -->
                    <div class="col-md-4 mb-3">
                        <label for="ciudad" class="form-label fw-bold">
                            Ciudad <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="ciudad" 
                               name="ciudad" 
                               required 
                               maxlength="100"
                               value="<?php echo htmlspecialchars($configuracion['ciudad']); ?>"
                               placeholder="Ciudad">
                    </div>
                    
                    <!-- Estado -->
                    <div class="col-md-4 mb-3">
                        <label for="estado" class="form-label fw-bold">
                            Estado <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="estado" 
                               name="estado" 
                               required 
                               maxlength="100"
                               value="<?php echo htmlspecialchars($configuracion['estado']); ?>"
                               placeholder="Estado">
                    </div>
                    
                    <!-- Código Postal -->
                    <div class="col-md-4 mb-3">
                        <label for="codigo_postal" class="form-label fw-bold">
                            Código Postal <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="codigo_postal" 
                               name="codigo_postal" 
                               required 
                               maxlength="10"
                               value="<?php echo htmlspecialchars($configuracion['codigo_postal']); ?>"
                               placeholder="00000">
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN: Contacto -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-phone me-2"></i>Información de Contacto
                </h5>
                <div class="row">
                    <!-- Teléfono -->
                    <div class="col-md-4 mb-3">
                        <label for="telefono" class="form-label fw-bold">Teléfono</label>
                        <input type="text" 
                               class="form-control" 
                               id="telefono" 
                               name="telefono" 
                               maxlength="20"
                               value="<?php echo htmlspecialchars($configuracion['telefono']); ?>"
                               placeholder="(000) 000-0000">
                    </div>
                    
                    <!-- Email -->
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               maxlength="100"
                               value="<?php echo htmlspecialchars($configuracion['email']); ?>"
                               placeholder="contacto@empresa.com">
                    </div>
                    
                    <!-- Sitio Web -->
                    <div class="col-md-4 mb-3">
                        <label for="sitio_web" class="form-label fw-bold">Sitio Web</label>
                        <input type="text" 
                               class="form-control" 
                               id="sitio_web" 
                               name="sitio_web" 
                               maxlength="150"
                               value="<?php echo htmlspecialchars($configuracion['sitio_web']); ?>"
                               placeholder="www.empresa.com">
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN: Logo -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">
                    <i class="fas fa-image me-2"></i>Logo de la Empresa
                </h5>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="logo_path" class="form-label fw-bold">Ruta del Logo</label>
                        <input type="text" 
                               class="form-control" 
                               id="logo_path" 
                               name="logo_path" 
                               maxlength="255"
                               value="<?php echo htmlspecialchars($configuracion['logo_path']); ?>"
                               placeholder="public/images/logoenlaza.jpg">
                        <small class="text-muted">
                            Ruta relativa al archivo de logo. Ej: public/images/logo.jpg
                        </small>
                    </div>
                    
                    <!-- Vista previa del logo -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Vista Previa</label>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; text-align: center;">
                            <img src="<?php echo BASE_URL . $configuracion['logo_path']; ?>" 
                                 alt="Logo" 
                                 id="logoPreview"
                                 style="max-width: 100%; max-height: 80px; background: white; padding: 10px; border-radius: 8px;"
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 200 60%22%3E%3Crect width=%22200%22 height=%2260%22 fill=%22%23e9ecef%22/%3E%3Ctext x=%22100%22 y=%2235%22 font-size=%2214%22 fill=%22%23666%22 text-anchor=%22middle%22%3ELogo no encontrado%3C/text%3E%3C/svg%3E';">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Info de última actualización -->
            <div class="alert alert-info mb-4">
                <i class="fas fa-clock me-2"></i>
                <strong>Última actualización:</strong> 
                <?php echo date('d/m/Y H:i:s', strtotime($configuracion['fecha_actualizacion'])); ?>
            </div>
            
            <!-- Botones -->
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-guardar">
                        <i class="fas fa-save me-2"></i>Guardar Configuración
                    </button>
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-cancelar">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </div>
            
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Convertir RFC a mayúsculas automáticamente
    $('#rfc').on('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    // Vista previa del logo al cambiar la ruta
    $('#logo_path').on('blur', function() {
        const nuevaRuta = $(this).val();
        if (nuevaRuta) {
            $('#logoPreview').attr('src', '<?php echo BASE_URL; ?>' + nuevaRuta);
        }
    });
    
    // Validación del formulario
    $('#formConfiguracion').on('submit', function(e) {
        const rfc = $('#rfc').val().trim();
        
        if (rfc.length < 12) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'RFC inválido',
                text: 'El RFC debe tener al menos 12 caracteres'
            });
            return false;
        }
    });
});
</script>

<?php
// Incluir footer
require_once VIEWS_PATH . 'layout/footer.php';
?>