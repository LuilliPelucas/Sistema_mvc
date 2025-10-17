<?php
/**
 * Controlador de Cotizaciones
 * 
 * Maneja todas las operaciones de cotizaciones
 * Incluye manejo de transacciones para encabezado y partidas
 * 
 * @author Tu Nombre
 * @version 1.0
 */

class CotizacionesController extends Controller {
    
    private $cotizacionModel;
    private $clienteModel;
    private $inventarioModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->cotizacionModel = $this->loadModel('Cotizacion');
        $this->clienteModel = $this->loadModel('Cliente');
        $this->inventarioModel = $this->loadModel('Inventario');
    }
    
    /**
     * Método index - Lista todas las cotizaciones
     */
    public function index() {
        $cotizaciones = $this->cotizacionModel->getAllConCliente();
        
        $datos = [
            'titulo' => 'Listado de Cotizaciones',
            'cotizaciones' => $cotizaciones
        ];
        
        $this->loadView('cotizaciones/index', $datos);
    }
    
    /**
     * Método crear - Muestra formulario y procesa la creación
     */
    public function crear() {
        // Obtener clientes para el select
        $clientes = $this->clienteModel->getAll();
        
        // Obtener inventarios activos para el select
        $inventarios = $this->inventarioModel->getActivos();
        
        // Si es POST, procesar el formulario
        if ($this->isPost()) {
            // Procesar datos del encabezado
            $datosEncabezado = [
                'clientesid' => $this->post('clientesid'),
                'folio' => '', // Se genera automáticamente
                'fecha' => $this->post('fecha'),
                'condiciones' => $this->post('condiciones'),
                'dias_vigencia' => $this->post('dias_vigencia'),
                'contacto' => $this->post('contacto'),
                'dias_entrega' => $this->post('dias_entrega'),
                'subtotal' => $this->post('subtotal', 0),
                'iva' => $this->post('iva', 0),
                'total' => $this->post('total', 0),
                'observaciones' => $this->post('observaciones')
            ];
            
            // Procesar partidas (vienen como JSON)
            $partidasJSON = $this->post('partidas_json');
            $partidas = json_decode($partidasJSON, true);
            
            // Validar
            $validacion = $this->cotizacionModel->validar($datosEncabezado, $partidas);
            
            if ($validacion['valido']) {
                // Crear cotización con transacción
                $resultado = $this->cotizacionModel->crearCotizacionCompleta($datosEncabezado, $partidas);
                
                if ($resultado['success']) {
                    $_SESSION['mensaje'] = $resultado['mensaje'] . ' - Folio generado';
                    $this->redirect('cotizaciones');
                } else {
                    $_SESSION['error'] = $resultado['error'];
                }
            } else {
                $_SESSION['error'] = implode('<br>', $validacion['errores']);
            }
        }
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Nueva Cotización',
            'clientes' => $clientes,
            'inventarios' => $inventarios
        ];
        
        $this->loadView('cotizaciones/crear', $datos);
    }
    
    /**
     * Método ver - Visualiza una cotización
     */
    public function ver($id) {
        $cotizacion = $this->cotizacionModel->getCotizacionConPartidas($id);
        
        if (!$cotizacion) {
            $_SESSION['error'] = 'Cotización no encontrada';
            $this->redirect('cotizaciones');
        }
        
        $datos = [
            'titulo' => 'Ver Cotización',
            'cotizacion' => $cotizacion
        ];
        
        $this->loadView('cotizaciones/ver', $datos);
    }
    
    /**
     * Método imprimir - Genera vista de impresión/PDF
     * AGREGAR ESTE MÉTODO al CotizacionesController.php
     *
     * @param int $id ID de la cotización
     */
    public function imprimir($id) {
        // Obtener la cotización con sus partidas
        $cotizacion = $this->cotizacionModel->getCotizacionConPartidas($id);
        
        // Verificar que exista
        if (!$cotizacion) {
            $_SESSION['error'] = 'Cotización no encontrada';
            $this->redirect('cotizaciones');
            return;
        }
        
        // Cargar modelo de configuración de empresa
        $configuracionModel = $this->loadModel('ConfiguracionEmpresa');
        $empresa = $configuracionModel->getConfiguracion();
        
        // Log para debug
        error_log("Imprimiendo cotización ID: " . $id);
        error_log("Empresa cargada: " . ($empresa ? $empresa['nombre_empresa'] : 'NO CARGADA'));
        
        // Si no hay configuración, usar valores por defecto
        if (!$empresa) {
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
        }
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Imprimir Cotización',
            'cotizacion' => $cotizacion,
            'empresa' => $empresa
        ];
        
        // Cargar vista de impresión (sin header ni footer del dashboard)
        $this->loadView('cotizaciones/imprimir', $datos);
    }

    public function obtenerArticulo() {
    // Log para debugging
    error_log("=== INICIO obtenerArticulo ===");
    error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
    error_log("HTTP_X_REQUESTED_WITH: " . ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? 'NO DEFINIDO'));
    error_log("POST recibido: " . print_r($_POST, true));
    
    // Verificar que sea petición AJAX
    if (!$this->isAjax()) {
        error_log("❌ NO ES AJAX - Redirigiendo");
        
        // Si no es AJAX, devolver error JSON en lugar de redireccionar
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'mensaje' => 'Esta es una petición AJAX solamente'
        ]);
        exit;
    }
    
    error_log("✅ Es petición AJAX");
    
    // Obtener código del artículo
    $codigoArticulo = $this->post('codigoarticulo');
    error_log("Código artículo recibido: '" . $codigoArticulo . "'");
    
    // Validar que venga el código
    if (empty($codigoArticulo)) {
        error_log("❌ Código vacío o null");
        $this->jsonResponse([
            'success' => false,
            'mensaje' => 'Código de artículo requerido'
        ], 400);
        return;
    }
    
    try {
        error_log("Intentando buscar artículo...");
        
        // Verificar que el modelo existe
        if (!$this->inventarioModel) {
            error_log("❌ ERROR: inventarioModel no está inicializado");
            $this->jsonResponse([
                'success' => false,
                'mensaje' => 'Error: Modelo de inventario no inicializado'
            ], 500);
            return;
        }
        
        error_log("Modelo inventario OK, ejecutando búsqueda...");
        
        // Buscar el artículo
        $articulos = $this->inventarioModel->buscar($codigoArticulo);
        
        error_log("Búsqueda completada. Artículos encontrados: " . count($articulos));
        
        if (!empty($articulos)) {
            $articulo = $articulos[0];
            
            error_log("✅ Artículo encontrado:");
            error_log("  - Código: " . $articulo['codigoarticulo']);
            error_log("  - Descripción: " . $articulo['descripcion']);
            error_log("  - Unidad: " . ($articulo['unidad'] ?? 'NO DEFINIDA'));
            
            $this->jsonResponse([
                'success' => true,
                'articulo' => [
                    'codigoarticulo' => $articulo['codigoarticulo'],
                    'descripcion' => $articulo['descripcion'],
                    'unidad' => $articulo['unidad'] ?? 'PZA',
                    'existencia' => $articulo['existencia'] ?? 0
                ]
            ]);
        } else {
            error_log("❌ Artículo NO encontrado con código: " . $codigoArticulo);
            $this->jsonResponse([
                'success' => false,
                'mensaje' => 'Artículo no encontrado: ' . $codigoArticulo
            ], 404);
        }
        
    } catch (Exception $e) {
        error_log("❌ EXCEPCIÓN en obtenerArticulo:");
        error_log("  - Mensaje: " . $e->getMessage());
        error_log("  - Archivo: " . $e->getFile());
        error_log("  - Línea: " . $e->getLine());
        error_log("  - Trace: " . $e->getTraceAsString());
        
        $this->jsonResponse([
            'success' => false,
            'mensaje' => 'Error al buscar el artículo',
            'error' => $e->getMessage(),
            'debug' => [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ], 500);
    }
    
    error_log("=== FIN obtenerArticulo ===");
    }
}
?>