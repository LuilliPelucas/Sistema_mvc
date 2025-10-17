<?php
/**
 * Header con Menú del Dashboard
 * 
 * Header reutilizable para todas las páginas del sistema
 * Incluye el menú de navegación completo del dashboard
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titulo) ? $titulo . ' - ' : ''; ?><?php echo SYSTEM_NAME; ?></title>
    
    <!-- Base URL para facilitar las rutas -->
    <base href="<?php echo BASE_URL; ?>">
    
    <!-- jQuery PRIMERO (obligatorio) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/styles.css">
    
    <script>
        // Debug: Verificar carga del logo
        document.addEventListener('DOMContentLoaded', function() {
            const logo = document.getElementById('logoHeader');
            if (logo) {
                console.log('✅ Logo encontrado en el DOM');
                console.log('📍 Ruta del logo:', logo.src);
                
                logo.addEventListener('load', function() {
                    console.log('✅ Logo cargado exitosamente');
                });
                
                logo.addEventListener('error', function() {
                    console.error('❌ Error al cargar el logo');
                    console.error('Ruta intentada:', logo.src);
                    // Mostrar mensaje visual de error
                    logo.style.border = '2px solid red';
                    logo.title = 'Error al cargar logo: ' + logo.src;
                });
            } else {
                console.error('❌ Elemento logo no encontrado en el DOM');
            }
        });
    </script>
    
    <style>
        /* Estilos mejorados para el header con logo */
        .dashboard-header {
            background: linear-gradient(135deg, #5c9ead, #7db8c7);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 5px 0;
        }
        
        .logo-img {
            height: 60px !important;
            width: auto !important;
            max-width: 250px !important;
            min-width: 150px !important;
            object-fit: contain !important;
            background: white !important;
            padding: 8px 15px !important;
            border-radius: 10px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
            transition: transform 0.3s !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        .logo-img:hover {
            transform: scale(1.05);
        }
        
        /* Eliminar regla que podría ocultar el logo */
        .logo-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .navbar {
            padding: 0;
        }
        
        .navbar-nav {
            gap: 5px;
        }
        
        .nav-link {
            color: white !important;
            padding: 10px 18px !important;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
            font-size: 0.95rem;
            white-space: nowrap;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.3);
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .nav-link i {
            margin-right: 8px;
            font-size: 1rem;
        }
        
        .nav-link-logout {
            background: rgba(220, 53, 69, 0.8) !important;
        }
        
        .nav-link-logout:hover {
            background: rgba(220, 53, 69, 1) !important;
            transform: translateY(-2px);
        }
        
        .navbar-toggler {
            border-color: white;
            background: rgba(255,255,255,0.2);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .dashboard-main {
            padding: 30px 0;
            min-height: calc(100vh - 100px);
            background: #f5f7fa;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .logo-img {
                height: 45px;
            }
            
            .navbar-collapse {
                background: rgba(92, 158, 173, 0.98);
                margin-top: 15px;
                padding: 15px;
                border-radius: 10px;
            }
            
            .nav-link {
                margin: 5px 0;
            }
        }
        
        @media (max-width: 576px) {
            .logo-img {
                height: 40px;
                max-width: 150px;
            }
            
            .dashboard-header {
                padding: 8px 0;
            }
        }
        
        /* Alertas mejoradas */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }
    </style>
</head>
<body>
    
    <!-- Header / Navbar del Dashboard -->
    <header class="dashboard-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-auto">
                    <div class="logo-container">
                        <img src="<?php echo BASE_URL; ?>public/images/logoenlaza.jpg" 
                             alt="Logo <?php echo SYSTEM_NAME; ?>" 
                             class="logo-img"
                             id="logoHeader">
                    </div>
                </div>
                
                <!-- Menú de Navegación -->
                <div class="col">
                    <nav class="navbar navbar-expand-lg">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" 
                                       href="<?php echo BASE_URL; ?>">
                                        <i class="fas fa-home"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'configuracion') !== false ? 'active' : ''; ?>" 
                                    href="<?php echo BASE_URL; ?>configuracion">
                                        <i class="fas fa-cog"></i>Configuración
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'clientes') !== false ? 'active' : ''; ?>" 
                                       href="<?php echo BASE_URL; ?>clientes">
                                        <i class="fas fa-users"></i>Clientes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'inventarios') !== false ? 'active' : ''; ?>" 
                                       href="<?php echo BASE_URL; ?>inventarios">
                                        <i class="fas fa-box"></i>Inventario
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'proveedores') !== false ? 'active' : ''; ?>" 
                                       href="<?php echo BASE_URL; ?>proveedores">
                                        <i class="fas fa-truck"></i>Proveedores
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'cotizaciones') !== false ? 'active' : ''; ?>" 
                                       href="<?php echo BASE_URL; ?>cotizaciones">
                                        <i class="fas fa-file-invoice"></i>Cotizaciones
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'remisiones') !== false ? 'active' : ''; ?>" 
                                       href="<?php echo BASE_URL; ?>remisiones">
                                        <i class="fas fa-file-alt"></i>Remisiones
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'facturas') !== false ? 'active' : ''; ?>" 
                                       href="<?php echo BASE_URL; ?>facturas">
                                        <i class="fas fa-receipt"></i>Facturas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-logout" href="<?php echo BASE_URL; ?>logout.php">
                                        <i class="fas fa-sign-out-alt"></i>Cerrar Sesión
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Contenedor principal con margen del dashboard -->
    <main class="dashboard-main">
        <div class="container-fluid">
        
        <?php
        // Mostrar mensajes de éxito
        if (isset($_SESSION['mensaje'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>' . $_SESSION['mensaje'] . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>';
            unset($_SESSION['mensaje']);
        }
        
        // Mostrar mensajes de error
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>' . $_SESSION['error'] . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>';
            unset($_SESSION['error']);
        }
        ?>