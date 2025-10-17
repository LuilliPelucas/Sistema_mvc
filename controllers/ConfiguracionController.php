<?php
/**
 * Controlador de Configuración de Empresa
 */

class ConfiguracionController extends Controller {
    
    private $configuracionModel;
    
    public function __construct() {
        $this->configuracionModel = $this->loadModel('ConfiguracionEmpresa');
    }
    
    /**
     * Método index - Muestra y edita la configuración
     */
    public function index() {
        // Obtener configuración actual
        $configuracion = $this->configuracionModel->getConfiguracion();
        
        // Si es POST, procesar actualización
        if ($this->isPost()) {
            $datos = [
                'nombre_empresa' => $this->post('nombre_empresa'),
                'rfc' => $this->post('rfc'),
                'direccion' => $this->post('direccion'),
                'colonia' => $this->post('colonia'),
                'ciudad' => $this->post('ciudad'),
                'estado' => $this->post('estado'),
                'codigo_postal' => $this->post('codigo_postal'),
                'telefono' => $this->post('telefono'),
                'email' => $this->post('email'),
                'sitio_web' => $this->post('sitio_web'),
                'logo_path' => $this->post('logo_path', 'public/images/logoenlaza.jpg')
            ];
            
            // Validar
            $validacion = $this->configuracionModel->validar($datos);
            
            if ($validacion['valido']) {
                $resultado = $this->configuracionModel->actualizarConfiguracion($datos);
                
                if ($resultado) {
                    $_SESSION['mensaje'] = 'Configuración actualizada exitosamente';
                    $this->redirect('configuracion');
                } else {
                    $_SESSION['error'] = 'Error al actualizar la configuración';
                }
            } else {
                $_SESSION['error'] = implode('<br>', $validacion['errores']);
            }
        }
        
        // Datos para la vista
        $datos = [
            'titulo' => 'Configuración de Empresa',
            'configuracion' => $configuracion
        ];
        
        $this->loadView('configuracion/index', $datos);
    }
}
?>