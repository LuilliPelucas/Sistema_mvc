<?php
/**
 * Controlador de Proveedores
 * 
 * Maneja todas las operaciones CRUD de la tabla proveedores
 * 
 * @author Tu Nombre
 * @version 1.0
 */

class ProveedoresController extends Controller {
    
    // Modelo de proveedores
    private $proveedorModel;
    
    /**
     * Constructor
     * Inicializa el modelo de proveedores
     */
    public function __construct() {
        $this->proveedorModel = $this->loadModel('Proveedor');
    }
    
    /**
     * Método index - Lista todos los proveedores
     * Ruta: /proveedores o /proveedores/index
     * 
     * @return void
     */
    public function index() {
        // Obtener todos los proveedores
        $proveedores = $this->proveedorModel->getAll();
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Listado de Proveedores',
            'proveedores' => $proveedores
        ];
        
        // Cargar la vista
        $this->loadView('proveedores/index', $datos);
    }
    
    /**
     * Método crear - Muestra el formulario y procesa la creación
     * Ruta: /proveedores/crear
     * 
     * @return void
     */
    public function crear() {
        // Si es una petición POST, procesar el formulario
        if ($this->isPost()) {
            // Obtener datos del formulario
            $datos = [
                'claveproveedor' => $this->post('claveproveedor'),
                'nombreproveedor' => $this->post('nombreproveedor'),
                'direccion' => $this->post('direccion'),
                'exterior' => $this->post('exterior'),
                'interior' => $this->post('interior'),
                'colonia' => $this->post('colonia'),
                'ciudad' => $this->post('ciudad'),
                'estado' => $this->post('estado')
            ];
            
            // Validar datos
            $validacion = $this->proveedorModel->validar($datos);

            if ($validacion['valido']) {
                // Verificar si la clave ya existe
                if ($this->proveedorModel->claveExiste($datos['claveproveedor'])) {
                    $_SESSION['error'] = 'La clave del proveedor ya existe';
                } else {
                    // Insertar proveedor
                    $resultado = $this->proveedorModel->insert($datos);

                    if ($resultado) {
                        $_SESSION['mensaje'] = 'Proveedor creado exitosamente';
                        $this->redirect('proveedores');
                    } else {
                        $_SESSION['error'] = 'Error al crear el proveedor';
                    }
                }
            } else {
                $_SESSION['error'] = implode('<br>', $validacion['errores']);
            }
        }
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Crear Proveedor'
        ];
        
        // Cargar la vista
        $this->loadView('proveedores/crear', $datos);
    }
    
    /**
     * Método editar - Muestra el formulario y procesa la edición
     * Ruta: /proveedores/editar/ID
     * 
     * @param int $id ID del proveedor a editar
     * @return void
     */
    public function editar($id) {
        // Obtener el proveedor
        $proveedor = $this->proveedorModel->getById($id);
        
        // Verificar si existe el proveedor
        if (!$proveedor) {
            $_SESSION['error'] = 'Proveedor no encontrado';
            $this->redirect('proveedores');
        }
        
        // Si es una petición POST, procesar el formulario
        if ($this->isPost()) {
            // Obtener datos del formulario
            $datos = [
                'claveproveedor' => $this->post('claveproveedor'),
                'nombreproveedor' => $this->post('nombreproveedor'),
                'direccion' => $this->post('direccion'),
                'exterior' => $this->post('exterior'),
                'interior' => $this->post('interior'),
                'colonia' => $this->post('colonia'),
                'ciudad' => $this->post('ciudad'),
                'estado' => $this->post('estado')
            ];
            
            // Validar datos
            $validacion = $this->proveedorModel->validar($datos);
            
            if ($validacion['valido']) {
                // Verificar si la clave ya existe (excluyendo el actual)
                if ($this->proveedorModel->claveExiste($datos['claveproveedor'], $id)) {
                    $_SESSION['error'] = 'La clave del proveedor ya existe';
                } else {
                    // Actualizar proveedor
                    $resultado = $this->proveedorModel->update($id, $datos);

                    if ($resultado) {
                        $_SESSION['mensaje'] = 'Proveedor actualizado exitosamente';
                        $this->redirect('proveedores');
                    } else {
                        $_SESSION['error'] = 'Error al actualizar el proveedor';
                    }
                }
            } else {
                $_SESSION['error'] = implode('<br>', $validacion['errores']);
            }
        }
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Editar Proveedor',
            'proveedor' => $proveedor
        ];
        
        // Cargar la vista
        $this->loadView('proveedores/editar', $datos);
    }
    
    /**
     * Método eliminar - Elimina un proveedor (vía AJAX)
     * Ruta: /proveedores/eliminar/ID
     * 
     * @param int $id ID del proveedor a eliminar
     * @return void
     */
    public function eliminar($id) {
        // Verificar que sea una petición AJAX
        if (!$this->isAjax()) {
            $this->redirect('proveedores');
        }

        // Eliminar el proveedor
        $resultado = $this->proveedorModel->delete($id);

        if ($resultado) {
            $this->jsonResponse([
                'success' => true,
                'mensaje' => 'Proveedor eliminado exitosamente'
            ]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'mensaje' => 'Error al eliminar el proveedor'
            ], 400);
        }
    }
}
?>