</div> <!-- Cierre del contenedor principal -->
    
    <!-- Footer -->
    <footer class="footer mt-5 py-4">
        <div class="container-fluid px-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        <i class="fas fa-copyright me-1"></i> <?php echo date('Y'); ?> <?php echo SYSTEM_NAME; ?>
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
    
    <!-- SCRIPTS EN ORDEN CORRECTO -->
    <!-- 1. jQuery PRIMERO -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- 2. Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- 3. DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <!-- 4. SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- 5. Scripts personalizados con versión para evitar caché -->
    <?php $version = time(); // Forzar recarga ?>
    <script src="<?php echo BASE_URL; ?>public/js/main.js?v=<?php echo $version; ?>"></script>
    
    <?php
    // Cargar scripts específicos según la URL
    $currentUrl = $_SERVER['REQUEST_URI'];
    
    if (strpos($currentUrl, 'clientes') !== false) {
        echo '<script src="' . BASE_URL . 'public/js/clientes.js?v=' . $version . '"></script>';
    }
    
    if (strpos($currentUrl, 'inventarios') !== false) {
        echo '<script src="' . BASE_URL . 'public/js/inventarios.js?v=' . $version . '"></script>';
    }
    ?>
    
</body>
</html>