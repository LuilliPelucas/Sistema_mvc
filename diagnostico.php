<?php
/**
 * ARCHIVO DE DIAGNÓSTICO
 * 
 * Guarda este archivo en la raíz del proyecto: sistema_mvc/diagnostico.php
 * Ábrelo en: http://localhost/sistema_mvc/diagnostico.php
 * 
 * Te mostrará si hay problemas con las rutas, AJAX o la base de datos
 */

session_start();
require_once 'config/config.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico del Sistema</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            line-height: 1.6;
        }
        .box {
            background: #252526;
            border-left: 4px solid #007acc;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .success { border-left-color: #4caf50; }
        .error { border-left-color: #f44336; }
        .warning { border-left-color: #ff9800; }
        h1 { color: #569cd6; }
        h2 { color: #4ec9b0; margin-top: 30px; }
        code {
            background: #1e1e1e;
            padding: 2px 6px;
            border-radius: 3px;
            color: #ce9178;
        }
        .test-result {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .test-pass { background: #1e3a1e; color: #4caf50; }
        .test-fail { background: #3a1e1e; color: #f44336; }
        button {
            background: #007acc;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
        }
        button:hover { background: #005a9e; }
        #ajaxResult {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico del Sistema MVC</h1>
    
    <div class="box">
        <h2>1️⃣ Verificación de Configuración</h2>
        
        <div class="test-result test-pass">
            ✅ BASE_URL: <code><?php echo BASE_URL; ?></code>
        </div>
        
        <div class="test-result test-pass">
            ✅ ROOT_PATH: <code><?php echo ROOT_PATH; ?></code>
        </div>
        
        <?php if (file_exists(ROOT_PATH . 'config/Database.php')): ?>
            <div class="test-result test-pass">
                ✅ Archivo Database.php existe
            </div>
        <?php else: ?>
            <div class="test-result test-fail">
                ❌ Archivo Database.php NO existe
            </div>
        <?php endif; ?>
    </div>
    
    <div class="box">
        <h2>2️⃣ Verificación de Conexión a Base de Datos</h2>
        
        <?php
        try {
            $database = new Database();
            $conn = $database->getConexion();
            
            if ($conn) {
                echo '<div class="test-result test-pass">';
                echo '✅ Conexión a MySQL exitosa (Puerto 3309, Password: SHAPES)';
                echo '</div>';
                
                // Verificar tablas
                $stmt = $conn->query("SHOW TABLES");
                $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                if (in_array('clientes', $tablas)) {
                    echo '<div class="test-result test-pass">✅ Tabla "clientes" existe</div>';
                } else {
                    echo '<div class="test-result test-fail">❌ Tabla "clientes" NO existe</div>';
                }
                
                if (in_array('inventarios', $tablas)) {
                    echo '<div class="test-result test-pass">✅ Tabla "inventarios" existe</div>';
                } else {
                    echo '<div class="test-result test-fail">❌ Tabla "inventarios" NO existe</div>';
                }
            }
        } catch (Exception $e) {
            echo '<div class="test-result test-fail">';
            echo '❌ Error de conexión: ' . $e->getMessage();
            echo '</div>';
            echo '<div class="test-result test-warning">';
            echo '⚠️ Verifica que MySQL esté corriendo en el puerto 3309';
            echo '</div>';
        }
        ?>
    </div>
    
    <div class="box">
        <h2>3️⃣ Verificación de Controladores</h2>
        
        <?php
        $controladores = ['ClientesController', 'InventariosController'];
        foreach ($controladores as $ctrl) {
            $archivo = CONTROLLERS_PATH . $ctrl . '.php';
            if (file_exists($archivo)) {
                echo '<div class="test-result test-pass">✅ ' . $ctrl . ' existe</div>';
                
                // Verificar que tenga el método eliminar
                require_once $archivo;
                if (method_exists($ctrl, 'eliminar')) {
                    echo '<div class="test-result test-pass">✅ ' . $ctrl . '::eliminar() existe</div>';
                } else {
                    echo '<div class="test-result test-fail">❌ ' . $ctrl . '::eliminar() NO existe</div>';
                }
            } else {
                echo '<div class="test-result test-fail">❌ ' . $ctrl . ' NO existe</div>';
            }
        }
        ?>
    </div>
    
    <div class="box">
        <h2>4️⃣ Verificación de mod_rewrite</h2>
        
        <?php
        if (function_exists('apache_get_modules')) {
            $modules = apache_get_modules();
            if (in_array('mod_rewrite', $modules)) {
                echo '<div class="test-result test-pass">✅ mod_rewrite está habilitado</div>';
            } else {
                echo '<div class="test-result test-fail">❌ mod_rewrite NO está habilitado</div>';
            }
        } else {
            echo '<div class="test-result test-warning">⚠️ No se puede verificar mod_rewrite (función no disponible)</div>';
        }
        
        if (file_exists(ROOT_PATH . '.htaccess')) {
            echo '<div class="test-result test-pass">✅ Archivo .htaccess existe</div>';
        } else {
            echo '<div class="test-result test-fail">❌ Archivo .htaccess NO existe</div>';
        }
        ?>
    </div>
    
    <div class="box">
        <h2>5️⃣ Prueba de Eliminación AJAX</h2>
        
        <p>Haz clic en los botones para probar las rutas de eliminación:</p>
        
        <button onclick="probarEliminacionClientes()">Probar Clientes/Eliminar</button>
        <button onclick="probarEliminacionInventarios()">Probar Inventarios/Eliminar</button>
        
        <div id="ajaxResult"></div>
    </div>
    
    <div class="box">
        <h2>6️⃣ Información del Servidor</h2>
        
        <div class="test-result test-pass">
            🖥️ PHP Version: <code><?php echo phpversion(); ?></code>
        </div>
        
        <div class="test-result test-pass">
            🌐 Server: <code><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido'; ?></code>
        </div>
        
        <div class="test-result test-pass">
            📂 Document Root: <code><?php echo $_SERVER['DOCUMENT_ROOT']; ?></code>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
        
        function probarEliminacionClientes() {
            const resultDiv = document.getElementById('ajaxResult');
            resultDiv.innerHTML = '⏳ Probando ruta de clientes...';
            
            $.ajax({
                url: BASE_URL + 'clientes/eliminar/999',
                type: 'POST',
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                },
                success: function(response) {
                    resultDiv.innerHTML = '✅ AJAX funciona correctamente\n\nRespuesta:\n' + JSON.stringify(response, null, 2);
                    resultDiv.style.color = '#4caf50';
                },
                error: function(xhr, status, error) {
                    resultDiv.innerHTML = '❌ Error en AJAX\n\n';
                    resultDiv.innerHTML += 'Status: ' + status + '\n';
                    resultDiv.innerHTML += 'Error: ' + error + '\n';
                    resultDiv.innerHTML += 'URL intentada: ' + BASE_URL + 'clientes/eliminar/999\n';
                    resultDiv.innerHTML += '\nRespuesta del servidor:\n' + xhr.responseText;
                    resultDiv.style.color = '#f44336';
                }
            });
        }
        
        function probarEliminacionInventarios() {
            const resultDiv = document.getElementById('ajaxResult');
            resultDiv.innerHTML = '⏳ Probando ruta de inventarios...';
            
            $.ajax({
                url: BASE_URL + 'inventarios/eliminar/999',
                type: 'POST',
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                },
                error: function(xhr, status, error) {
                    resultDiv.innerHTML = '❌ Error en AJAX\n\n';
                    resultDiv.innerHTML += 'Status: ' + status + '\n';
                    resultDiv.innerHTML += 'Error: ' + error + '\n';
                    resultDiv.innerHTML += 'URL intentada: ' + BASE_URL + 'inventarios/eliminar/999\n';
                    resultDiv.innerHTML += '\nRespuesta del servidor:\n' + xhr.responseText;
                    resultDiv.style.color = '#f44336';
                }
            });
        }
    </script>
</body>
</html>