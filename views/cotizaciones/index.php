<?php
/**
 * Vista: Listado de Cotizaciones
 */

// Incluir header del dashboard
require_once VIEWS_PATH . 'layout/header_dashboard.php';
?>

<div class="card card-custom">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1"><i class="fas fa-file-invoice me-2"></i><?php echo $titulo; ?></h3>
                <p class="mb-0 opacity-75">Gestión de cotizaciones del sistema</p>
            </div>
            <a href="<?php echo BASE_URL; ?>cotizaciones/crear" class="btn btn-crear">
                <i class="fas fa-plus me-2"></i>Nueva Cotización
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="tablaCotizaciones" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Condiciones</th>
                        <th>Total</th>
                        <th>Partidas</th>
                        <th>Status</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cotizaciones as $cotizacion): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($cotizacion['folio']); ?></strong></td>
                        <td><?php echo htmlspecialchars($cotizacion['nombrecliente']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($cotizacion['fecha'])); ?></td>
                        <td>
                            <span class="badge <?php echo $cotizacion['condiciones'] == 'Credito' ? 'bg-warning' : 'bg-success'; ?>">
                                <?php echo $cotizacion['condiciones']; ?>
                            </span>
                        </td>
                        <td class="text-end"><strong>$<?php echo number_format($cotizacion['total'], 2); ?></strong></td>
                        <td class="text-center"><?php echo $cotizacion['total_partidas']; ?></td>
                        <td>
                            <?php
                            $statusColors = [
                                'Pendiente' => 'bg-info',
                                'Aprobada' => 'bg-success',
                                'Rechazada' => 'bg-danger',
                                'Vencida' => 'bg-secondary'
                            ];
                            $color = $statusColors[$cotizacion['status']] ?? 'bg-secondary';
                            ?>
                            <span class="badge <?php echo $color; ?>">
                                <?php echo $cotizacion['status']; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="<?php echo BASE_URL; ?>cotizaciones/ver/<?php echo $cotizacion['cotizacionesid']; ?>" 
                                   class="btn btn-sm btn-info" 
                                   title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>cotizaciones/imprimir/<?php echo $cotizacion['cotizacionesid']; ?>" 
                                   target="_blank"
                                   class="btn btn-sm btn-imprimir" 
                                   title="Imprimir">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tablaCotizaciones').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[0, 'desc']],
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 1 },
            { responsivePriority: 3, targets: 7 }
        ]
    });
});
</script>

<?php
// Incluir footer
require_once VIEWS_PATH . 'layout/footer.php';
?>

