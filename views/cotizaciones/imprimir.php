<?php
/**
 * Vista: Imprimir Cotización
 * Genera una vista optimizada para imprimir o guardar como PDF
 * 
 * Variables disponibles:
 * - $cotizacion: Datos de la cotización con partidas
 * - $empresa: Configuración de la empresa
 */

// Los datos ya vienen del controlador
// Si por alguna razón no vienen, usar valores por defecto
if (!isset($empresa) || !$empresa) {
    error_log("⚠️ Variable \$empresa no definida en imprimir.php");
    $empresa = [
        'nombre_empresa' => 'Tu Empresa S.A. de C.V.',
        'rfc' => 'XXXX-XXXXXX-XXX',
        'direccion' => 'Dirección no configurada',
        'colonia' => '',
        'ciudad' => 'Ciudad',
        'estado' => 'Estado',
        'codigo_postal' => '00000',
        'telefono' => '',
        'email' => '',
        'logo_path' => 'public/images/logoenlaza.jpg'
    ];
} else {
    error_log("✅ Datos de empresa cargados: " . $empresa['nombre_empresa']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización <?php echo $cotizacion['folio']; ?> - Impresión</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #333;
            padding: 10mm;
            background: white;
            max-width: 210mm;
            margin: 0 auto;
        }
        
        /* Header del documento */
        .document-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #5c9ead;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-logo {
            width: 140px;
            height: auto;
            margin-bottom: 8px;
        }
        
        .company-name {
            font-size: 14pt;
            font-weight: bold;
            color: #5c9ead;
            margin-bottom: 4px;
        }
        
        .company-details {
            font-size: 8pt;
            color: #666;
            line-height: 1.4;
        }
        
        .quotation-info {
            text-align: right;
        }
        
        .quotation-title {
            font-size: 15pt;
            font-weight: bold;
            color: #5c9ead;
            margin-bottom: 6px;
        }
        
        .quotation-folio {
            font-size: 9pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 4px;
        }
        
        .quotation-date {
            font-size: 8pt;
            color: #666;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 7pt;
            margin-top: 6px;
        }
        
        .status-pendiente { background: #fff3cd; color: #856404; }
        .status-aprobada { background: #d4edda; color: #155724; }
        .status-rechazada { background: #f8d7da; color: #721c24; }
        .status-vencida { background: #e2e3e5; color: #383d41; }
        
        /* Información del cliente */
        .client-section {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            margin-top: 8px;
        }
        
        .section-title {
            font-size: 8pt;
            font-weight: bold;
            color: #5c9ead;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .section-title i {
            font-size: 11pt;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
            line-height: 1.3;
        }
        
        .info-item {
            display: flex;
            gap: 6px;
            font-size: 8pt;
        }
        
        .info-label {
            font-weight: bold;
            color: #5c9ead;
            min-width: 80px;
        }
        
        .info-value {
            color: #333;
        }
        
        /* Tabla de partidas */
        .partidas-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8pt;
        }
        
        .partidas-table thead {
            background: linear-gradient(135deg, #5c9ead, #7db8c7);
            color: white;
        }
        
        .partidas-table th {
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            font-size: 8pt;
            border: 1px solid #ddd;
        }
        
        .partidas-table td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            font-size: 8pt;
        }
        
        .partidas-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .partidas-table tbody tr:hover {
            background-color: #e3f2fd;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Totales */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }
        
        .totals-box {
            width: 280px;
            background: linear-gradient(135deg, #5c9ead, #7db8c7);
            color: white;
            padding: 12px;
            border-radius: 8px;
        }
        
        .total-line {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px solid rgba(255,255,255,0.3);
            font-size: 8pt;
        }
        
        .total-line:last-child {
            border-bottom: none;
        }
        
        .total-line.final {
            border-top: 2px solid white;
            margin-top: 6px;
            padding-top: 8px;
            font-size: 8pt;
            font-weight: bold;
        }
        
        /* Observaciones de partida */
        .observaciones-partida {
            background: #fffbf0;
            padding: 6px 8px;
            margin-top: 4px;
            margin-bottom: 8px;
            border-left: 3px solid #ffc107;
            font-size: 7pt;
            font-style: italic;
            color: #666;
        }
        
        /* Observaciones */
        .observations-section {
            background: #fff9e6;
            padding: 10px;
            border-left: 3px solid #ffc107;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 8pt;
        }
        
        .observations-section h4 {
            color: #856404;
            margin-bottom: 6px;
            font-size: 9pt;
        }
        
        /* Términos y condiciones */
        .terms-section {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            font-size: 7pt;
            color: #666;
        }
        
        .terms-section h4 {
            color: #333;
            margin-bottom: 6px;
            font-size: 8pt;
        }
        
        .terms-section ul {
            margin-left: 15px;
            margin-bottom: 0;
        }
        
        .terms-section li {
            margin-bottom: 3px;
        }
        
        /* Footer */
        .document-footer {
            position: fixed;
            bottom: 8mm;
            left: 10mm;
            right: 10mm;
            padding-top: 8px;
            border-top: 1px solid #5c9ead;
            font-size: 7pt;
            color: #666;
            display: flex;
            justify-content: space-between;
        }
        
        /* Botones de acción (no se imprimen) */
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-print {
            background: linear-gradient(135deg, #5c9ead, #7db8c7);
            color: white;
        }
        
        .btn-print:hover {
            background: linear-gradient(135deg, #4a7c89, #5c9ead);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(92, 158, 173, 0.3);
        }
        
        .btn-close {
            background: #6c757d;
            color: white;
        }
        
        .btn-close:hover {
            background: #5a6268;
        }
        
        /* Estilos de impresión */
        @media print {
            body {
                padding: 8mm;
            }
            
            .action-buttons {
                display: none;
            }
            
            .document-footer {
                position: fixed;
                bottom: 8mm;
            }
            
            .partidas-table {
                page-break-inside: auto;
            }
            
            .partidas-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            .totals-section,
            .observations-section,
            .terms-section {
                page-break-inside: avoid;
            }
        }
        
        @page {
            size: letter;
            margin: 12mm;
        }
    </style>
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    
    <!-- Botones de acción (solo para pantalla) -->
    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Imprimir / Guardar PDF
        </button>
        <button onclick="window.close()" class="btn btn-close">
            <i class="fas fa-times"></i> Cerrar
        </button>
    </div>
    
    <!-- Header del documento -->
    <div class="document-header">
        <div class="company-info">
            <?php if (!empty($empresa['logo_path'])): ?>
                <img src="<?php echo BASE_URL . $empresa['logo_path']; ?>" 
                     alt="Logo Empresa" 
                     class="company-logo"
                     onerror="this.style.display='none'">
            <?php endif; ?>
            
            <div class="company-name">
                <?php echo htmlspecialchars($empresa['nombre_empresa']); ?>
            </div>
            
            <div class="company-details">
                <strong>RFC:</strong> <?php echo htmlspecialchars($empresa['rfc']); ?><br>
                <strong>Dirección:</strong> <?php echo htmlspecialchars($empresa['direccion']); ?>
                <?php if (!empty($empresa['colonia'])): ?>
                    , <?php echo htmlspecialchars($empresa['colonia']); ?>
                <?php endif; ?><br>
                <?php echo htmlspecialchars($empresa['ciudad']); ?>, 
                <?php echo htmlspecialchars($empresa['estado']); ?>, 
                CP <?php echo htmlspecialchars($empresa['codigo_postal']); ?><br>
                
                <?php if (!empty($empresa['telefono'])): ?>
                    <strong>Tel:</strong> <?php echo htmlspecialchars($empresa['telefono']); ?><br>
                <?php endif; ?>
                
                <?php if (!empty($empresa['email'])): ?>
                    <strong>Email:</strong> <?php echo htmlspecialchars($empresa['email']); ?>
                <?php endif; ?>
                
                <?php if (!empty($empresa['sitio_web'])): ?>
                    <br><strong>Web:</strong> <?php echo htmlspecialchars($empresa['sitio_web']); ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="quotation-info">
            <div class="quotation-title">COTIZACIÓN</div>
            <div class="quotation-folio"><?php echo htmlspecialchars($cotizacion['folio']); ?></div>
            <div class="quotation-date">
                <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($cotizacion['fecha'])); ?>
            </div>
            <div>
                <?php
                $statusClass = [
                    'Pendiente' => 'status-pendiente',
                    'Aprobada' => 'status-aprobada',
                    'Rechazada' => 'status-rechazada',
                    'Vencida' => 'status-vencida'
                ];
                $class = $statusClass[$cotizacion['status']] ?? 'status-pendiente';
                ?>
                <span class="status-badge <?php echo $class; ?>">
                    <?php echo $cotizacion['status']; ?>
                </span>
            </div>
        </div>
    </div>
    
    <!-- Información del cliente -->
    <div class="client-section">
        <div class="section-title">
            <i class="fas fa-user-circle"></i>
            INFORMACIÓN DEL CLIENTE
        </div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Cliente:</span>
                <span class="info-value"><?php echo htmlspecialchars($cotizacion['nombrecliente']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Clave:</span>
                <span class="info-value"><?php echo htmlspecialchars($cotizacion['clavecliente']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Contacto:</span>
                <span class="info-value"><?php echo htmlspecialchars($cotizacion['contacto'] ?: 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Condiciones:</span>
                <span class="info-value"><?php echo $cotizacion['condiciones']; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Vigencia:</span>
                <span class="info-value"><?php echo $cotizacion['dias_vigencia']; ?> días</span>
            </div>
            <div class="info-item">
                <span class="info-label">Entrega:</span>
                <span class="info-value"><?php echo $cotizacion['dias_entrega']; ?> días</span>
            </div>
        </div>
    </div>
    
    <!-- Tabla de partidas -->
    <table class="partidas-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 10%;">Código</th>
                <th style="width: 30%;">Descripción</th>
                <th style="width: 8%;">Unidad</th>
                <th style="width: 8%;" class="text-right">Cant.</th>
                <th style="width: 10%;" class="text-right">P. Unit.</th>
                <th style="width: 10%;" class="text-right">Subtotal</th>
                <th style="width: 7%;" class="text-center">IVA</th>
                <th style="width: 12%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($cotizacion['partidas'])): ?>
                <tr>
                    <td colspan="9" class="text-center" style="padding: 30px; color: #999;">
                        No hay partidas registradas
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($cotizacion['partidas'] as $partida): ?>
                <tr>
                    <td class="text-center"><strong><?php echo $partida['partida_numero']; ?></strong></td>
                    <td><?php echo htmlspecialchars($partida['codigoarticulo']); ?></td>
                    <td><?php echo htmlspecialchars($partida['descripcion']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($partida['unidad_medida']); ?></td>
                    <td class="text-right"><?php echo number_format($partida['cantidad'], 2); ?></td>
                    <td class="text-right">$<?php echo number_format($partida['precio_unitario'], 2); ?></td>
                    <td class="text-right"><strong>$<?php echo number_format($partida['precio_total'], 2); ?></strong></td>
                    <td class="text-center"><?php echo number_format($partida['porcentaje_iva'], 0); ?>%</td>
                    <td class="text-right"><strong>$<?php echo number_format($partida['total_partida'], 2); ?></strong></td>
                </tr>
                <?php if (!empty($partida['observaciones_partida'])): ?>
                <tr>
                    <td colspan="9" style="padding: 0; border-top: none;">
                        <div class="observaciones-partida">
                            <strong>Observaciones:</strong> <?php echo nl2br(htmlspecialchars($partida['observaciones_partida'])); ?>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Totales -->
    <div class="totals-section">
        <div class="totals-box">
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
    
    <!-- Observaciones -->
    <?php if (!empty($cotizacion['observaciones'])): ?>
    <div class="observations-section">
        <h4><i class="fas fa-comment-alt"></i> Observaciones:</h4>
        <p><?php echo nl2br(htmlspecialchars($cotizacion['observaciones'])); ?></p>
    </div>
    <?php endif; ?>
    
    <!-- Términos y condiciones -->
    <div class="terms-section">
        <h4>Términos y Condiciones:</h4>
        <ul>
            <li>Esta cotización tiene una vigencia de <?php echo $cotizacion['dias_vigencia']; ?> días a partir de la fecha de emisión.</li>
            <li>Tiempo de entrega: <?php echo $cotizacion['dias_entrega']; ?> días hábiles.</li>
            <li>Condiciones de pago: <?php echo $cotizacion['condiciones']; ?>.</li>
            <li>Los precios están sujetos a cambio sin previo aviso.</li>
            <li>Una vez aceptada la cotización, no se aceptarán cancelaciones.</li>
        </ul>
    </div>
    
    <!-- Footer -->
    <div class="document-footer">
        <div>
            <strong><?php echo isset($empresa['nombre_empresa']) ? htmlspecialchars($empresa['nombre_empresa']) : 'Sistema de Gestión'; ?></strong><br>
            Generado el: <?php echo date('d/m/Y H:i:s'); ?>
        </div>
        <div style="text-align: right;">
            <strong>Página 1 de 1</strong>
        </div>
    </div>
    
    <script>
        // Auto-imprimir si viene el parámetro en la URL
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('auto') === 'print') {
                setTimeout(() => {
                    window.print();
                }, 500);
            }
        };
    </script>
</body>
</html>