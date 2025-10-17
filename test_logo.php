<?php
require_once 'config/config.php';

echo "<h2>Diagnóstico de Logo</h2>";
echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<p><strong>Ruta completa del logo:</strong> " . BASE_URL . "public/images/logoenlaza.jpg</p>";

$rutaArchivo = __DIR__ . '/public/images/logoenlaza.jpg';
echo "<p><strong>Ruta física:</strong> " . $rutaArchivo . "</p>";

if (file_exists($rutaArchivo)) {
    echo "<p style='color: green;'>✅ El archivo existe</p>";
    echo "<img src='" . BASE_URL . "public/images/logoenlaza.jpg' style='max-width: 300px;'>";
} else {
    echo "<p style='color: red;'>❌ El archivo NO existe</p>";
    echo "<p>Archivos en /public/images/:</p>";
    
    if (is_dir(__DIR__ . '/public/images/')) {
        $archivos = scandir(__DIR__ . '/public/images/');
        echo "<ul>";
        foreach ($archivos as $archivo) {
            if ($archivo != '.' && $archivo != '..') {
                echo "<li>" . $archivo . "</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>La carpeta /public/images/ no existe</p>";
    }
}
?>