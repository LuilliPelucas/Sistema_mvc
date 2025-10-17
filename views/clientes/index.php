<?php
/**
 * Vista: Listado de Clientes
 * 
 * Muestra todos los clientes en una tabla con DataTables
 * Permite crear, editar y eliminar clientes
 */

// Incluir header del dashboard
require_once VIEWS_PATH . 'layout/header_dashboard.php';
?>

<div class="card card-custom">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1"><i class="fas fa-users me-2"></i><?php echo $titulo; ?></h3>
                <p class="mb-0 opacity-75">Gestión de clientes del sistema</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo BASE_URL; ?>clientes/imprimir" 
                   target="_blank" 
                   class="btn btn-imprimir">
                    <i class="fas fa-print me-2"></i>Imprimir PDF
                </a>
                <a href="<?php echo BASE_URL; ?>clientes/crear" class="btn btn-crear">
                    <i class="fas fa-plus me-2"></i>Nuevo Cliente
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="tablaClientes" class="table table-striped table-hover" style="width:100%">
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
                    <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente['clientesid']; ?></td>
                        <td><strong><?php echo htmlspecialchars($cliente['clavecliente']); ?></strong></td>
                        <td><?php echo htmlspecialchars($cliente['nombrecliente']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['ciudad']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['estado']); ?></td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="<?php echo BASE_URL; ?>clientes/editar/<?php echo $cliente['clientesid']; ?>" 
                                   class="btn btn-sm btn-editar" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-eliminar" 
                                        data-id="<?php echo $cliente['clientesid']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($cliente['nombrecliente']); ?>"
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

<!-- JavaScript específico de la página (después del footer) -->
<script src="<?php echo BASE_URL; ?>public/js/clientes.js"></script>

<?php
// Incluir footer
require_once VIEWS_PATH . 'layout/footer.php';
?>