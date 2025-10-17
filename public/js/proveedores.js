/**
 * JavaScript para el módulo de Proveedores
 * 
 * Maneja la inicialización de DataTables y las acciones CRUD
 * de la tabla de proveedores
 * 
 * @author Tu Nombre
 * @version 1.0
 */

// Ejecutar cuando el documento esté listo
$(document).ready(function() {
    
    /**
     * Inicializar DataTable de Proveedores
     * Configuración personalizada para la tabla de proveedores
     */
    if ($('#tablaProveedores').length) {
        $('#tablaProveedores').DataTable({
            // Diseño responsive
            responsive: true,
            
            // Idioma en español
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            
            // Cantidad de registros por página
            pageLength: 10,
            
            // Opciones de registros por página
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            
            // Ordenamiento por defecto (por ID descendente)
            order: [[0, 'desc']],
            
            // Configuración de columnas
            columnDefs: [
                { 
                    responsivePriority: 1, 
                    targets: 0  // Columna ID
                },
                { 
                    responsivePriority: 2, 
                    targets: 2  // Columna Nombre
                },
                { 
                    responsivePriority: 3, 
                    targets: 5  // Columna Acciones
                },
                { 
                    orderable: false, 
                    targets: 5  // Desactivar ordenamiento en Acciones
                }
            ],
            
            // Opciones adicionales
            autoWidth: false,
            scrollX: true
        });
    }
    
    /**
     * Manejador del botón Eliminar
     * Utiliza delegación de eventos para elementos dinámicos
     */
    $(document).on('click', '.btn-eliminar', function() {
        // Obtener datos del botón
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const fila = $(this).closest('tr');
        
        // Mostrar confirmación
        confirmar(
            '¿Eliminar Proveedor?',
            `¿Estás seguro de eliminar al proveedor "${nombre}"? Esta acción no se puede deshacer.`,
            'warning',
            function() {
                // Ejecutar eliminación si el usuario confirma
                eliminarProveedor(id, fila);
            }
        );
    });
    
});

/**
 * Elimina un proveedor mediante AJAX
 * 
 * @param {number} id - ID del proveedor a eliminar
 * @param {object} fila - Elemento TR de la fila a eliminar
 */
function eliminarProveedor(id, fila) {
    // Obtener la URL base desde PHP
    const baseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/') + '/';
    
    // Petición AJAX para eliminar
    $.ajax({
        url: baseUrl + 'proveedores/eliminar/' + id,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Remover la fila de la tabla con animación
                fila.fadeOut(400, function() {
                    // Destruir y reconstruir DataTable para actualizar
                    const table = $('#tablaProveedores').DataTable();
                    table.row(fila).remove().draw();
                });
                
                // Mostrar mensaje de éxito
                toast(response.mensaje, 'success');
            } else {
                notificar('Error', response.mensaje, 'error');
            }
        },
        error: function(xhr, status, error) {
            notificar('Error', 'Ocurrió un error al eliminar el proveedor', 'error');
            console.error('Error AJAX:', error);
        }
    });
}

/**
 * Validación del formulario de proveedores
 * Se ejecuta antes de enviar el formulario
 */
if ($('#formProveedor').length) {
    $('#formProveedor').on('submit', function(e) {
        // Validar campos requeridos
        if (!validarFormulario('formProveedor')) {
            e.preventDefault();
            return false;
        }
        
        // Desactivar botón de envío para evitar doble clic
        const btnSubmit = $(this).find('button[type="submit"]');
        btnSubmit.prop('disabled', true);
        btnSubmit.html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');
    });
}