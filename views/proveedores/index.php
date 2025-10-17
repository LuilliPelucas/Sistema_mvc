<?php
/**
 * Vista: Listado de Proveedores
 * 
 * Muestra todos los proveedores en una tabla con DataTables
 * Permite crear, editar y eliminar proveedores
 */

// Incluir header
require_once VIEWS_PATH . 'layout/header.php';
?>

<div class="card card-custom">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1"><i class="fas fa-users me-2"></i><?php echo $titulo; ?></h3>
                <p class="mb-0 opacity-75">Gestión de proveedores del sistema</p>
            </div>
            <a href="<?php echo BASE_URL; ?>proveedores/crear" class="btn btn-crear">
                <i class="fas fa-plus me-2"></i>Nuevo Proveedor
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="tablaProveedores" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Ciudad</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proveedores as $proveedor): ?>
                    <tr>
                        <td><?php echo $proveedor['proveedoresid']; ?></td>
                        <td><strong><?php echo htmlspecialchars($proveedor['claveproveedor']); ?></strong></td>
                        <td><?php echo htmlspecialchars($proveedor['nombreproveedor']); ?></td>
                        <td><?php echo htmlspecialchars($proveedor['ciudad']); ?></td>
                        <td><?php echo htmlspecialchars($proveedor['estado']); ?></td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="<?php echo BASE_URL; ?>proveedores/editar/<?php echo $proveedor['proveedoresid']; ?>" 
                                   class="btn btn-sm btn-editar" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-eliminar" 
                                        data-id="<?php echo $proveedor['proveedoresid']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($proveedor['nombreproveedor']); ?>"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JavaScript específico de la página -->
<script src="<?php echo BASE_URL; ?>public/js/proveedores.js"></script>

<?php
// Incluir footer
require_once VIEWS_PATH . 'layout/footer.php';
?>