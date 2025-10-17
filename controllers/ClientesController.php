<?php
/**
 * Controlador de Clientes
 * 
 * Maneja todas las operaciones CRUD de la tabla clientes
 * 
 * @author Tu Nombre
 * @version 1.0
 */

class ClientesController extends Controller {
    
    // Modelo de clientes
    private $clienteModel;
    
    /**
     * Constructor
     * Inicializa el modelo de clientes
     */
    public function __construct() {
        $this->clienteModel = $this->loadModel('Cliente');
    }
    
    /**
     * Método index - Lista todos los clientes
     * Ruta: /clientes o /clientes/index
     * 
     * @return void
     */
    public function index() {
        // Obtener todos los clientes
        $clientes = $this->clienteModel->getAll();
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Listado de Clientes',
            'clientes' => $clientes
        ];
        
        // Cargar la vista
        $this->loadView('clientes/index', $datos);
    }
    
    /**
     * Método crear - Muestra el formulario y procesa la creación
     * Ruta: /clientes/crear
     * 
     * @return void
     */
    public function crear() {
        // Si es una petición POST, procesar el formulario
        if ($this->isPost()) {
            // Obtener datos del formulario
            $datos = [
                'clavecliente' => $this->post('clavecliente'),
                'nombrecliente' => $this->post('nombrecliente'),
                'direccion' => $this->post('direccion'),
                'exterior' => $this->post('exterior'),
                'interior' => $this->post('interior'),
                'colonia' => $this->post('colonia'),
                'ciudad' => $this->post('ciudad'),
                'estado' => $this->post('estado')
            ];
            
            // Validar datos
            $validacion = $this->clienteModel->validar($datos);
            
            if ($validacion['valido']) {
                // Verificar si la clave ya existe
                if ($this->clienteModel->claveExiste($datos['clavecliente'])) {
                    $_SESSION['error'] = 'La clave del cliente ya existe';
                } else {
                    // Insertar cliente
                    $resultado = $this->clienteModel->insert($datos);
                    
                    if ($resultado) {
                        $_SESSION['mensaje'] = 'Cliente creado exitosamente';
                        $this->redirect('clientes');
                    } else {
                        $_SESSION['error'] = 'Error al crear el cliente';
                    }
                }
            } else {
                $_SESSION['error'] = implode('<br>', $validacion['errores']);
            }
        }
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Crear Cliente'
        ];
        
        // Cargar la vista
        $this->loadView('clientes/crear', $datos);
    }
    
    /**
     * Método editar - Muestra el formulario y procesa la edición
     * Ruta: /clientes/editar/ID
     * 
     * @param int $id ID del cliente a editar
     * @return void
     */
    public function editar($id) {
        // Obtener el cliente
        $cliente = $this->clienteModel->getById($id);
        
        // Verificar si existe el cliente
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente no encontrado';
            $this->redirect('clientes');
        }
        
        // Si es una petición POST, procesar el formulario
        if ($this->isPost()) {
            // Obtener datos del formulario
            $datos = [
                'clavecliente' => $this->post('clavecliente'),
                'nombrecliente' => $this->post('nombrecliente'),
                'direccion' => $this->post('direccion'),
                'exterior' => $this->post('exterior'),
                'interior' => $this->post('interior'),
                'colonia' => $this->post('colonia'),
                'ciudad' => $this->post('ciudad'),
                'estado' => $this->post('estado')
            ];
            
            // Validar datos
            $validacion = $this->clienteModel->validar($datos);
            
            if ($validacion['valido']) {
                // Verificar si la clave ya existe (excluyendo el actual)
                if ($this->clienteModel->claveExiste($datos['clavecliente'], $id)) {
                    $_SESSION['error'] = 'La clave del cliente ya existe';
                } else {
                    // Actualizar cliente
                    $resultado = $this->clienteModel->update($id, $datos);
                    
                    if ($resultado) {
                        $_SESSION['mensaje'] = 'Cliente actualizado exitosamente';
                        $this->redirect('clientes');
                    } else {
                        $_SESSION['error'] = 'Error al actualizar el cliente';
                    }
                }
            } else {
                $_SESSION['error'] = implode('<br>', $validacion['errores']);
            }
        }
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Editar Cliente',
            'cliente' => $cliente
        ];
        
        // Cargar la vista
        $this->loadView('clientes/editar', $datos);
    }
    
    /**
     * Método eliminar - Elimina un cliente (vía AJAX)
     * Ruta: /clientes/eliminar/ID
     * 
     * @param int $id ID del cliente a eliminar
     * @return void
     */
    public function eliminar($id) {
        // Verificar que sea una petición AJAX
        if (!$this->isAjax()) {
            $this->redirect('clientes');
        }
        
        // Eliminar el cliente
        $resultado = $this->clienteModel->delete($id);
        
        if ($resultado) {
            $this->jsonResponse([
                'success' => true,
                'mensaje' => 'Cliente eliminado exitosamente'
            ]);
        } else {
            $this->jsonResponse([
                'success' => false,
                'mensaje' => 'Error al eliminar el cliente'
            ], 400);
        }
    }
    
    /**
     * Método imprimir - Genera vista de impresión/PDF de clientes
     * Ruta: /clientes/imprimir
     * 
     * @return void
     */
    public function imprimir() {
        // Obtener todos los clientes
        $clientes = $this->clienteModel->getAll();
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Listado de Clientes - Impresión',
            'clientes' => $clientes
        ];
        
        // Cargar la vista de impresión (sin header ni footer)
        $this->loadView('clientes/imprimir', $datos);
    }
}
?>