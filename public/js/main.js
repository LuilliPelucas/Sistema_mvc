/**
 * JavaScript Principal del Sistema MVC
 * 
 * Contiene funciones globales y utilidades compartidas
 * por todas las páginas del sistema
 * 
 * @author Tu Nombre
 * @version 1.0
 */

// Ejecutar cuando el documento esté listo
$(document).ready(function() {
    
    /**
     * Configuración global de AJAX
     * Envía siempre un header para identificar peticiones AJAX
     */
    $.ajaxSetup({
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    
    /**
     * Auto-cerrar alertas después de 5 segundos
     */
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    /**
     * Tooltips de Bootstrap
     * Habilita todos los tooltips del sistema
     */
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
});

/**
 * Muestra un mensaje de confirmación con SweetAlert2
 * 
 * @param {string} titulo - Título del mensaje
 * @param {string} texto - Texto del mensaje
 * @param {string} icono - Tipo de icono (warning, success, error, info)
 * @param {function} callback - Función a ejecutar si el usuario confirma
 * 
 * @example
 * confirmar('¿Eliminar?', '¿Estás seguro?', 'warning', function() {
 *     // Código a ejecutar si confirma
 * });
 */
function confirmar(titulo, texto, icono, callback) {
    Swal.fire({
        title: titulo,
        text: texto,
        icon: icono,
        showCancelButton: true,
        confirmButtonColor: '#5c9ead',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}

/**
 * Muestra un mensaje de notificación con SweetAlert2
 * 
 * @param {string} titulo - Título del mensaje
 * @param {string} texto - Texto del mensaje
 * @param {string} icono - Tipo de icono (success, error, warning, info)
 * 
 * @example
 * notificar('Éxito', 'Registro guardado correctamente', 'success');
 */
function notificar(titulo, texto, icono) {
    Swal.fire({
        title: titulo,
        text: texto,
        icon: icono,
        confirmButtonColor: '#5c9ead',
        confirmButtonText: 'Aceptar'
    });
}

/**
 * Muestra un mensaje toast (notificación pequeña)
 * 
 * @param {string} mensaje - Mensaje a mostrar
 * @param {string} icono - Tipo de icono (success, error, warning, info)
 * 
 * @example
 * toast('Registro eliminado', 'success');
 */
function toast(mensaje, icono) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: icono,
        title: mensaje
    });
}

/**
 * Valida un formulario antes de enviarlo
 * 
 * @param {string} formId - ID del formulario a validar
 * @return {boolean} - True si es válido, false si no
 * 
 * @example
 * if (validarFormulario('formCliente')) {
 *     // Enviar formulario
 * }
 */
function validarFormulario(formId) {
    const form = document.getElementById(formId);
    
    if (!form) {
        console.error('Formulario no encontrado: ' + formId);
        return false;
    }
    
    // Usar validación HTML5
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        notificar('Error', 'Por favor completa todos los campos requeridos', 'error');
        return false;
    }
    
    return true;
}

/**
 * Formatea un número con separador de miles
 * 
 * @param {number} numero - Número a formatear
 * @param {number} decimales - Cantidad de decimales (default: 2)
 * @return {string} - Número formateado
 * 
 * @example
 * formatearNumero(1234.56, 2); // Retorna "1,234.56"
 */
function formatearNumero(numero, decimales = 2) {
    return Number(numero).toLocaleString('en-US', {
        minimumFractionDigits: decimales,
        maximumFractionDigits: decimales
    });
}

/**
 * Desactiva un botón temporalmente para evitar doble clic
 * 
 * @param {string} btnId - ID del botón
 * @param {number} tiempo - Tiempo en milisegundos (default: 2000)
 * 
 * @example
 * desactivarBoton('btnGuardar', 3000);
 */
function desactivarBoton(btnId, tiempo = 2000) {
    const btn = document.getElementById(btnId);
    
    if (btn) {
        btn.disabled = true;
        const textoOriginal = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
        
        setTimeout(function() {
            btn.disabled = false;
            btn.innerHTML = textoOriginal;
        }, tiempo);
    }
}

/**
 * Convierte una cadena a formato de moneda
 * 
 * @param {number} cantidad - Cantidad a formatear
 * @param {string} moneda - Código de moneda (default: 'USD')
 * @return {string} - Cantidad formateada
 * 
 * @example
 * formatearMoneda(1234.56); // Retorna "$1,234.56"
 */
function formatearMoneda(cantidad, moneda = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: moneda
    }).format(cantidad);
}