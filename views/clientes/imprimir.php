<?php
/**
 * Vista: Imprimir Listado de Clientes
 * 
 * Genera un PDF con el listado de todos los clientes
 * Diseño profesional y optimizado para impresión
 */

// NO incluir header ni footer, esta es una página independiente
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes - Impresión</title>
    
    <style>
        /* Estilos para impresión */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
            padding: 20mm;
        }
        
        /* Header del documento */
        .document-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #5c9ead;
        }
        
        .logo-section {
            flex: 0 0 150px;
        }
        
        .logo {
            width: 120px;
            height: auto;
        }
        
        .title-section {
            flex: 1;
            text-align: center;
        }
        
        .document-title {
            font-size: 24pt;
            font-weight: bold;
            color: #5c9ead;
            margin-bottom: 5px;
        }
        
        .document-subtitle {
            font-size: 12pt;
            color: #666;
        }
        
        .info-section {
            flex: 0 0 150px;
            text-align: right;
            font-size: 9pt;
            color: #666;
        }
        
        /* Tabla de clientes */
        .clientes-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .clientes-table thead {
            background: linear-gradient(135deg, #5c9ead, #7db8c7);
            color: white;
        }
        
        .clientes-table th {
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10pt;
            border: 1px solid #ddd;
        }
        
        .clientes-table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            font-size: 9pt;
        }
        
        .clientes-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .clientes-table tbody tr:hover {
            background-color: #e8f4f8;
        }
        
        .cliente-id {
            font-weight: bold;
            color: #5c9ead;
        }
        
        .cliente-clave {
            font-weight: bold;
            color: #333;
        }
        
        /* Footer del documento */
        .document-footer {
            position: fixed;
            bottom: 15mm;
            left: 20mm;
            right: 20mm;
            padding-top: 10px;
            border-top: 2px solid #5c9ead;
            font-size: 9pt;
            color: #666;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .footer-left {
            text-align: left;
        }
        
        .footer-center {
            text-align: center;
        }
        
        .footer-right {
            text-align: right;
        }
        
        /* Estilos para impresión */
        @media print {
            body {
                padding: 15mm;
            }
            
            .no-print {
                display: none;
            }
            
            .document-footer {
                position: fixed;
                bottom: 10mm;
            }
            
            /* Evitar saltos de página dentro de las filas */
            .clientes-table tr {
                page-break-inside: avoid;
            }
        }
        
        /* Botones de acción (solo en pantalla) */
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .btn {
            padding: 12px 24px;
            margin-left: 10px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-print {
            background: linear-gradient(135deg, #5c9ead, #7db8c7);
            color: white;
        }
        
        .btn-print:hover {
            background: linear-gradient(135deg, #4a7c89, #5c9ead);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(92, 158, 173, 0.3);
        }
        
        .btn-close {
            background: #6c757d;
            color: white;
        }
        
        .btn-close:hover {
            background: #5a6268;
        }
        
        @media print {
            .action-buttons {
                display: none;
            }
        }
        
        /* Estadísticas */
        .statistics {
            margin-bottom: 20px;
            padding: 15px;
            background: #e8f4f8;
            border-radius: 10px;
            display: flex;
            justify-content: space-around;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 24pt;
            font-weight: bold;
            color: #5c9ead;
        }
        
        .stat-label {
            font-size: 10pt;
            color: #666;
        }
    </style>
</head>
<body>
    
    <!-- Botones de acción (solo visible en pantalla) -->
    <div class="action-buttons no-print">
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Imprimir / Guardar PDF
        </button>
        <button onclick="window.close()" class="btn btn-close">
            <i class="fas fa-times"></i> Cerrar
        </button>
    </div>
    
    <!-- Header del documento -->
    <div class="document-header">
        <div class="logo-section">
            <!-- Logo de la empresa -->
            <img src="<?php echo BASE_URL; ?>public/images/logoenlaza.jpg" 
                alt="Logo Empresa" 
                class="logo"
                style="width: 220px; height: auto;">
        </div>
                
        <div class="title-section">
            <div class="document-title">Listado de Clientes</div>
            <div class="document-subtitle"><?php echo SYSTEM_NAME; ?></div>
        </div>
        
        <div class="info-section">
            <div><strong>Fecha:</strong> <?php echo date('d/m/Y'); ?></div>
            <div><strong>Hora:</strong> <?php echo date('H:i:s'); ?></div>
            <div><strong>Usuario:</strong> Admin</div>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="statistics no-print">
        <div class="stat-item">
            <div class="stat-number"><?php echo count($clientes); ?></div>
            <div class="stat-label">Total de Clientes</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">
                <?php 
                $ciudades = array_unique(array_column($clientes, 'ciudad'));
                echo count(array_filter($ciudades)); 
                ?>
            </div>
            <div class="stat-label">Ciudades</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">
                <?php 
                $estados = array_unique(array_column($clientes, 'estado'));
                echo count(array_filter($estados)); 
                ?>
            </div>
            <div class="stat-label">Estados</div>
        </div>
    </div>
    
    <!-- Tabla de clientes -->
    <table class="clientes-table">
        <thead>
            <tr>
                <th style="width: 8%;">ID</th>
                <th style="width: 12%;">Clave</th>
                <th style="width: 25%;">Nombre</th>
                <th style="width: 25%;">Dirección</th>
                <th style="width: 5%;">Ext.</th>
                <th style="width: 5%;">Int.</th>
                <th style="width: 10%;">Ciudad</th>
                <th style="width: 10%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clientes)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px; color: #999;">
                        No hay clientes registrados
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td class="cliente-id"><?php echo $cliente['clientesid']; ?></td>
                    <td class="cliente-clave"><?php echo htmlspecialchars($cliente['clavecliente']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['nombrecliente']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['direccion'] ?: '-'); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($cliente['exterior'] ?: '-'); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($cliente['interior'] ?: '-'); ?></td>
                    <td><?php echo htmlspecialchars($cliente['ciudad'] ?: '-'); ?></td>
                    <td><?php echo htmlspecialchars($cliente['estado'] ?: '-'); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Footer del documento -->
    <div class="document-footer">
        <div class="footer-left">
            <strong><?php echo SYSTEM_NAME; ?></strong><br>
            Versión <?php echo SYSTEM_VERSION; ?>
        </div>
        <div class="footer-center">
            <strong>Fecha de impresión:</strong><br>
            <?php echo date('d/m/Y H:i:s'); ?>
        </div>
        <div class="footer-right">
            <strong>Página:</strong><br>
            <span class="page-number"></span>
        </div>
    </div>
    
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        // Añadir número de página automáticamente
        window.onload = function() {
            const pageNumbers = document.querySelectorAll('.page-number');
            pageNumbers.forEach(element => {
                element.textContent = '1 de 1';
            });
            
            // Auto-imprimir si se especifica en la URL
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