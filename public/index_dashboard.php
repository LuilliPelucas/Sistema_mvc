<?php
/**
 * Dashboard Principal del Sistema
 * 
 * Página de inicio con estadísticas y accesos rápidos
 */

session_start();
require_once 'config/config.php';

// Cargar modelos para obtener estadísticas
require_once MODELS_PATH . 'Cliente.php';
require_once MODELS_PATH . 'Inventario.php';

$clienteModel = new Cliente();
$inventarioModel = new Inventario();

// Obtener estadísticas
$totalClientes = $clienteModel->count();
$totalInventarios = $inventarioModel->count();
$inventariosActivos = count($inventarioModel->getActivos());
$existenciaBaja = count($inventarioModel->getExistenciaBaja());

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo SYSTEM_NAME; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Personalizado del Dashboard -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/dashboard.css">
</head>
<body>
    
    <!-- Header / Navbar -->
    <header class="dashboard-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-auto">
                    <div class="logo-container">
                        <img src="<?php echo BASE_URL; ?>public/images/logo.jpg" 
                             alt="Logo" 
                             class="logo-img"
                             onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><circle cx=%2250%22 cy=%2250%22 r=%2245%22 fill=%22%235c9ead%22/><text x=%2250%22 y=%2270%22 font-size=%2240%22 fill=%22white%22 text-anchor=%22middle%22 font-weight=%22bold%22>MVC</text></svg>'">
                        <span class="logo-text"><?php echo SYSTEM_NAME; ?></span>
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
                                    <a class="nav-link active" href="<?php echo BASE_URL; ?>">
                                        <i class="fas fa-home me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo BASE_URL; ?>clientes">
                                        <i class="fas fa-users me-2"></i>Clientes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo BASE_URL; ?>inventarios">
                                        <i class="fas fa-box me-2"></i>Inventario
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo BASE_URL; ?>proveedores">
                                        <i class="fas fa-truck me-2"></i>Proveedores
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo BASE_URL; ?>cotizaciones">
                                        <i class="fas fa-file-invoice me-2"></i>Cotizaciones
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo BASE_URL; ?>remisiones">
                                        <i class="fas fa-file-alt me-2"></i>Remisiones
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo BASE_URL; ?>facturas">
                                        <i class="fas fa-receipt me-2"></i>Facturas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-logout" href="<?php echo BASE_URL; ?>logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Contenido Principal -->
    <main class="dashboard-main">
        <div class="container-fluid">
            
            <!-- Título de Bienvenida -->
            <div class="welcome-section">
                <h1 class="welcome-title">
                    <i class="fas fa-chart-line me-3"></i>Dashboard
                </h1>
                <p class="welcome-subtitle">
                    Bienvenido al sistema de gestión - <?php echo date('d/m/Y H:i'); ?>
                </p>
            </div>
            
            <!-- Tarjetas de Estadísticas -->
            <div class="row g-4 mb-5">
                
                <!-- Card Clientes -->
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card stat-card-blue">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number"><?php echo number_format($totalClientes); ?></h3>
                            <p class="stat-label">Total Clientes</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>clientes" class="stat-link">
                            Ver detalles <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Card Inventario -->
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card stat-card-green">
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number"><?php echo number_format($totalInventarios); ?></h3>
                            <p class="stat-label">Artículos en Inventario</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>inventarios" class="stat-link">
                            Ver detalles <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Card Artículos Activos -->
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card stat-card-purple">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number"><?php echo number_format($inventariosActivos); ?></h3>
                            <p class="stat-label">Artículos Activos</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>inventarios" class="stat-link">
                            Ver detalles <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Card Existencia Baja -->
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card stat-card-red">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number"><?php echo number_format($existenciaBaja); ?></h3>
                            <p class="stat-label">Existencia Baja</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>inventarios" class="stat-link">
                            Ver detalles <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                
            </div>
            
            <!-- Accesos Rápidos -->
            <div class="quick-access-section">
                <h2 class="section-title">
                    <i class="fas fa-bolt me-2"></i>Accesos Rápidos
                </h2>
                
                <div class="row g-4">
                    
                    <!-- Botón Clientes -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="<?php echo BASE_URL; ?>clientes" class="quick-link">
                            <div class="quick-link-icon quick-link-blue">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="quick-link-title">Clientes</h4>
                            <p class="quick-link-desc">Gestionar clientes</p>
                        </a>
                    </div>
                    
                    <!-- Botón Inventario -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="<?php echo BASE_URL; ?>inventarios" class="quick-link">
                            <div class="quick-link-icon quick-link-green">
                                <i class="fas fa-box"></i>
                            </div>
                            <h4 class="quick-link-title">Inventario</h4>
                            <p class="quick-link-desc">Control de stock</p>
                        </a>
                    </div>
                    
                    <!-- Botón Proveedores -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="<?php echo BASE_URL; ?>proveedores" class="quick-link">
                            <div class="quick-link-icon quick-link-orange">
                                <i class="fas fa-truck"></i>
                            </div>
                            <h4 class="quick-link-title">Proveedores</h4>
                            <p class="quick-link-desc">Administrar proveedores</p>
                        </a>
                    </div>
                    
                    <!-- Botón Cotizaciones -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="<?php echo BASE_URL; ?>cotizaciones" class="quick-link">
                            <div class="quick-link-icon quick-link-purple">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <h4 class="quick-link-title">Cotizaciones</h4>
                            <p class="quick-link-desc">Crear cotizaciones</p>
                        </a>
                    </div>
                    
                    <!-- Botón Remisiones -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="<?php echo BASE_URL; ?>remisiones" class="quick-link">
                            <div class="quick-link-icon quick-link-cyan">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h4 class="quick-link-title">Remisiones</h4>
                            <p class="quick-link-desc">Gestionar remisiones</p>
                        </a>
                    </div>
                    
                    <!-- Botón Facturas -->
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="<?php echo BASE_URL; ?>facturas" class="quick-link">
                            <div class="quick-link-icon quick-link-red">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <h4 class="quick-link-title">Facturas</h4>
                            <p class="quick-link-desc">Administrar facturas</p>
                        </a>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="dashboard-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        <i class="fas fa-copyright me-1"></i> <?php echo date('Y'); ?> <?php echo SYSTEM_NAME; ?> - Todos los derechos reservados
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">
                        <i class="fas fa-code me-1"></i> Versión <?php echo SYSTEM_VERSION; ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js para futuras gráficas (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- JavaScript del Dashboard -->
    <script src="<?php echo BASE_URL; ?>public/js/dashboard.js"></script>
    
</body>
</html>