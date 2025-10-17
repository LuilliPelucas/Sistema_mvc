/**
 * JavaScript para el módulo de Inventarios
 * Versión corregida y funcional
 */

// Protección contra carga duplicada
if (typeof window.inventariosJsCargado === 'undefined') {
    window.inventariosJsCargado = true;
    
    console.log('Cargando inventarios.js...');
    console.log('jQuery disponible:', typeof $ !== 'undefined');

    // Ejecutar cuando el documento esté listo
    $(document).ready(function() {
        
        console.log('Document ready - inventarios');
        
        // Inicializar DataTable
        if ($('#tablaInventarios').length) {
            console.log('Inicializando DataTable de inventarios...');
            
            if ($.fn.DataTable.isDataTable('#tablaInventarios')) {
                $('#tablaInventarios').DataTable().destroy();
            }
            
            $('#tablaInventarios').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                order: [[2, 'asc']],
                columnDefs: [
                    { responsivePriority: 1, targets: 1 },
                    { responsivePriority: 2, targets: 2 },
                    { responsivePriority: 3, targets: 3 },
                    { responsivePriority: 4, targets: 8 },
                    { orderable: false, targets: 8 }
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
                    eliminarInventario(id, fila);
                }
                return;
            }
            
            // Mostrar confirmación
            Swal.fire({
                title: '¿Eliminar Artículo?',
                text: '¿Estás seguro de eliminar ' + nombre + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5c9ead',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminarInventario(id, fila);
                }
            });
        });
        
        // Validación de formulario
        if ($('#formInventario').length) {
            $('#formInventario').on('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    this.classList.add('was-validated');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'Completa todos los campos requeridos', 'error');
                    }
                    return false;
                }
                
                const existencia = parseFloat($('#existencia').val());
                const minimo = parseFloat($('#minimo').val());
                const maximo = parseFloat($('#maximo').val());
                
                if (isNaN(existencia) || isNaN(minimo) || isNaN(maximo)) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'Los valores deben ser números válidos', 'error');
                    }
                    return false;
                }
                
                if (minimo > maximo) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'El mínimo no puede ser mayor al máximo', 'error');
                    }
                    return false;
                }
                
                const btnSubmit = $(this).find('button[type="submit"]');
                btnSubmit.prop('disabled', true);
                btnSubmit.html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');
            });
        }
        
    });

    // Función para eliminar inventario
    function eliminarInventario(id, fila) {
        console.log('Eliminando inventario ID:', id);
        
        // Obtener BASE_URL
        let baseUrl = '';
        const baseTag = document.querySelector('base');
        
        if (baseTag && baseTag.href) {
            baseUrl = baseTag.href;
        } else {
            const pathArray = window.location.pathname.split('/');
            baseUrl = window.location.origin + '/' + pathArray[1] + '/';
        }
        
        const url = baseUrl + 'inventarios/eliminar/' + id;
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
                        const table = $('#tablaInventarios').DataTable();
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
                    Swal.fire('Error', 'Error al eliminar el artículo', 'error');
                } else {
                    alert('Error al eliminar');
                }
            }
        });
    }

    console.log('inventarios.js cargado completamente');
    
} else {
    console.warn('inventarios.js ya estaba cargado');
}