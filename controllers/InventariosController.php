<?php
/**
 * Controlador de Inventarios
 * 
 * Maneja todas las operaciones CRUD de la tabla inventarios
 * 
 * @author Tu Nombre
 * @version 1.0
 */

class InventariosController extends Controller {
    
    // Modelo de inventarios
    private $inventarioModel;
    
    /**
     * Constructor
     * Inicializa el modelo de inventarios
     */
    public function __construct() {
        $this->inventarioModel = $this->loadModel('Inventario');
    }
    
    /**
     * Método index - Lista todos los inventarios
     * Ruta: /inventarios o /inventarios/index
     * 
     * @return void
     */
    public function index() {
        // Obtener todos los inventarios
        $inventarios = $this->inventarioModel->getAll();
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Listado de Inventarios',
            'inventarios' => $inventarios
        ];
        
        // Cargar la vista
        $this->loadView('inventarios/index', $datos);
    }
    
    /**
     * Método crear - Muestra el formulario y procesa la creación
     * Ruta: /inventarios/crear
     * 
     * @return void
     */
    public function crear() {
        // Si es una petición POST, procesar el formulario
        if ($this->isPost()) {
            // Obtener datos del formulario
            $datos = [
                'codigoarticulo' => $this->post('codigoarticulo'),
                'descripcion' => $this->post('descripcion'),
                'existencia' => $this->post('existencia', 0),
                'minimo' => $this->post('minimo', 0),
                'maximo' => $this->post('maximo', 0),
                'unidad' => $this->post('unidad'),
                'precio_costo' => $this->post('precio_costo', 0),
                'precio_venta' => $this->post('precio_venta', 0),
                'moneda' => $this->post('moneda', 'PESOS'),
                'activo' => $this->post('activo', 1)
            ];
            
            // Validar datos
            $validacion = $this->inventarioModel->validar($datos);
            
            if ($validacion['valido']) {
                // Verificar si el código ya existe
                if ($this->inventarioModel->codigoExiste($datos['codigoarticulo'])) {
                    $_SESSION['error'] = 'El código del artículo ya existe';
                } else {
                    // Insertar artículo
                    $resultado = $this->inventarioModel->insert($datos);
                    
                    if ($resultado) {
                        $_SESSION['mensaje'] = 'Artículo creado exitosamente';
                        $this->redirect('inventarios');
                    } else {
                        $_SESSION['error'] = 'Error al crear el artículo';
                    }
                }
            } else {
                $_SESSION['error'] = implode('<br>', $validacion['errores']);
            }
        }
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Crear Artículo'
        ];
        
        // Cargar la vista
        $this->loadView('inventarios/crear', $datos);
    }
    
    /**
     * Método editar - Muestra el formulario y procesa la edición
     * Ruta: /inventarios/editar/ID
     * 
     * @param int $id ID del artículo a editar
     * @return void
     */
    public function editar($id) {
        // Obtener el artículo
        $inventario = $this->inventarioModel->getById($id);
        
        // Verificar si existe el artículo
        if (!$inventario) {
            $_SESSION['error'] = 'Artículo no encontrado';
            $this->redirect('inventarios');
        }
        
        // Si es una petición POST, procesar el formulario
        if ($this->isPost()) {
            // Obtener datos del formulario
            $datos = [
                'codigoarticulo' => $this->post('codigoarticulo'),
                'descripcion' => $this->post('descripcion'),
                'existencia' => $this->post('existencia', 0),
                'minimo' => $this->post('minimo', 0),
                'maximo' => $this->post('maximo', 0),
                'unidad' => $this->post('unidad'),
                'precio_costo' => $this->post('precio_costo', 0),
                'precio_venta' => $this->post('precio_venta', 0),
                'moneda' => $this->post('moneda', 'PESOS'),
                'activo' => $this->post('activo', 1)
            ];            
            // Validar datos
            $validacion = $this->inventarioModel->validar($datos);
            
            if ($validacion['valido']) {
                // Verificar si el código ya existe (excluyendo el actual)
                if ($this->inventarioModel->codigoExiste($datos['codigoarticulo'], $id)) {
                    $_SESSION['error'] = 'El código del artículo ya existe';
                } else {
                    // Actualizar artículo
                    $resultado = $this->inventarioModel->update($id, $datos);
                    
                    if ($resultado) {
                        $_SESSION['mensaje'] = 'Artículo actualizado exitosamente';
                        $this->redirect('inventarios');
                    } else {
                        $_SESSION['error'] = 'Error al actualizar el artículo';
                    }
                }
            } else {
                $_SESSION['error'] = implode('<br>', $validacion['errores']);
            }
        }
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Editar Artículo',
            'inventario' => $inventario
        ];
        
        // Cargar la vista
        $this->loadView('inventarios/editar', $datos);
    }
    
    /**
     * Método eliminar - Elimina un artículo (vía AJAX)
     * Ruta: /inventarios/eliminar/ID
     * 
     * @param int $id ID del artículo a eliminar
     * @return void
     */
    public function eliminar($id) {
        // Verificar que sea una petición AJAX
        if (!$this->isAjax()) {
            $this->redirect('inventarios');
        }
        
        // Eliminar el artículo
        $resultado = $this->inventarioModel->delete($id);
        
        if ($resultado) {
            $this->jsonResponse([
                'success' => true,
                'mensaje' => 'Artículo eliminado exitosamente'
            ]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'mensaje' => 'Error al eliminar el artículo'
            ], 400);
        }
    }
    
    /**
     * Método imprimir - Genera vista de impresión/PDF de inventarios
     * Ruta: /inventarios/imprimir
     * 
     * @return void
     */
    public function imprimir() {
        // Obtener todos los inventarios
        $inventarios = $this->inventarioModel->getAll();
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Listado de Inventarios - Impresión',
            'inventarios' => $inventarios
        ];
        
        // Cargar la vista de impresión
        $this->loadView('inventarios/imprimir', $datos);
    }
}
?>