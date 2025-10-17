/**
 * JavaScript para el módulo de Clientes
 * Versión corregida y funcional
 */

// Protección contra carga duplicada
if (typeof window.clientesJsCargado === 'undefined') {
    window.clientesJsCargado = true;
    
    console.log('Cargando clientes.js...');
    console.log('jQuery disponible:', typeof $ !== 'undefined');

    // Ejecutar cuando el documento esté listo
    $(document).ready(function() {
        
        console.log('Document ready - clientes');
        
        // Inicializar DataTable
        if ($('#tablaClientes').length) {
            console.log('Inicializando DataTable de clientes...');
            
            if ($.fn.DataTable.isDataTable('#tablaClientes')) {
                $('#tablaClientes').DataTable().destroy();
            }
            
            $('#tablaClientes').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                order: [[0, 'desc']],
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: 2 },
                    { responsivePriority: 3, targets: 5 },
                    { orderable: false, targets: 5 }
                ],
                autoWidth: false,
                scrollX: true
            });
            
            console.log('DataTable inicializado');
        }
        
        // Manejador del botón Eliminar
        $(document).on('click', '.btn-eliminar', function() {
            console.log('Click en eliminar');
            
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const fila = $(this).closest('tr');
            
            console.log('ID:', id, 'Nombre:', nombre);
            
            // Verificar SweetAlert
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 no disponible');
                if (confirm('¿Eliminar ' + nombre + '?')) {
                    eliminarCliente(id, fila);
                }
                return;
            }
            
            // Mostrar confirmación
            Swal.fire({
                title: '¿Eliminar Cliente?',
                text: '¿Estás seguro de eliminar a ' + nombre + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5c9ead',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminarCliente(id, fila);
                }
            });
        });
        
        // Validación de formulario
        if ($('#formCliente').length) {
            $('#formCliente').on('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    this.classList.add('was-validated');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'Completa todos los campos requeridos', 'error');
                    }
                    return false;
                }
                
                const btnSubmit = $(this).find('button[type="submit"]');
                btnSubmit.prop('disabled', true);
                btnSubmit.html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');
            });
        }
        
    });

    // Función para eliminar cliente
    function eliminarCliente(id, fila) {
        console.log('Eliminando cliente ID:', id);
        
        // Obtener BASE_URL
        let baseUrl = '';
        const baseTag = document.querySelector('base');
        
        if (baseTag && baseTag.href) {
            baseUrl = baseTag.href;
        } else {
            const pathArray = window.location.pathname.split('/');
            baseUrl = window.location.origin + '/' + pathArray[1] + '/';
        }
        
        const url = baseUrl + 'clientes/eliminar/' + id;
        console.log('URL AJAX:', url);
        
        // Petición AJAX
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(response) {
                console.log('Respuesta:', response);
                
                if (response.success) {
                    fila.fadeOut(400, function() {
                        const table = $('#tablaClientes').DataTable();
                        table.row(fila).remove().draw();
                    });
                    
                    if (typeof Swal !== 'undefined') {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: response.mensaje
                        });
                    } else {
                        alert(response.mensaje);
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', response.mensaje, 'error');
                    } else {
                        alert('Error: ' + response.mensaje);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', status, error);
                console.error('Respuesta:', xhr.responseText);
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', 'Error al eliminar el cliente', 'error');
                } else {
                    alert('Error al eliminar');
                }
            }
        });
    }

    console.log('clientes.js cargado completamente');
    
} else {
    console.warn('clientes.js ya estaba cargado');
}