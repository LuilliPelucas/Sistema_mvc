<?php
/**
 * Vista: Listado de Inventarios
 * 
 * Muestra todos los artículos del inventario en una tabla con DataTables
 * Permite crear, editar y eliminar artículos
 */

// Incluir header del dashboard
require_once VIEWS_PATH . 'layout/header_dashboard.php';
?>

<div class="card card-custom">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1"><i class="fas fa-box me-2"></i><?php echo $titulo; ?></h3>
                <p class="mb-0 opacity-75">Gestión de inventario del sistema</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo BASE_URL; ?>inventarios/imprimir" 
                   target="_blank" 
                   class="btn btn-imprimir">
                    <i class="fas fa-print me-2"></i>Imprimir PDF
                </a>
                <a href="<?php echo BASE_URL; ?>inventarios/crear" class="btn btn-crear">
                    <i class="fas fa-plus me-2"></i>Nuevo Artículo
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="tablaInventarios" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Existencia</th>
                        <th>Mínimo</th>
                        <th>Máximo</th>
                        <th>Unidad</th>
                        <th>Precio Costo</th>
                        <th>Precio Venta</th>
                        <th>Moneda</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventarios as $inventario): ?>
                    <tr>
                        <td><?php echo $inventario['inventariosid']; ?></td>
                        <td><strong><?php echo htmlspecialchars($inventario['codigoarticulo']); ?></strong></td>
                        <td><?php echo htmlspecialchars($inventario['descripcion']); ?></td>
                        <td class="text-end">
                            <?php 
                            $existencia = $inventario['existencia'];
                            $minimo = $inventario['minimo'];
                            $clase = $existencia < $minimo ? 'text-danger fw-bold' : '';
                            echo "<span class='$clase'>" . number_format($existencia, 2) . "</span>"; 
                            ?>
                        </td>
                        <td class="text-end"><?php echo number_format($inventario['minimo'], 2); ?></td>
                        <td class="text-end"><?php echo number_format($inventario['maximo'], 2); ?></td>
                        <td><?php echo htmlspecialchars($inventario['unidad']); ?></td>
                        <td class="text-end">$<?php echo number_format($inventario['precio_costo'], 2); ?></td>
                        <td class="text-end">$<?php echo number_format($inventario['precio_venta'], 2); ?></td>
                        <td><?php echo $inventario['moneda']; ?></td>
                        <td>
                            <?php if ($inventario['activo'] == 1): ?>
                                <span class="badge badge-activo">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-inactivo">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="<?php echo BASE_URL; ?>inventarios/editar/<?php echo $inventario['inventariosid']; ?>" 
                                   class="btn btn-sm btn-editar" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-eliminar" 
                                        data-id="<?php echo $inventario['inventariosid']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($inventario['descripcion']); ?>"
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
<script src="<?php echo BASE_URL; ?>public/js/inventarios.js"></script>

<?php
// Incluir footer
require_once VIEWS_PATH . 'layout/footer.php';
?>