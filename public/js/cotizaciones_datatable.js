/**
 * JavaScript para Cotizaciones con DataTable
 * Versión profesional y optimizada
 */

let tablaPartidas;
let partidas = [];
let contadorPartidas = 0;
let editandoIndex = -1;

$(document).ready(function() {
    console.log('Módulo de cotizaciones con DataTable cargado');
    
    // Inicializar DataTable
    inicializarDataTable();
    
    // Validar formulario antes de enviar
    $('#formCotizacion').on('submit', function(e) {
        e.preventDefault();
        
        if (validarFormulario()) {
            if (partidas.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Sin partidas',
                    text: 'Debe agregar al menos una partida a la cotización'
                });
                return false;
            }
            
            // Guardar partidas en campo oculto
            $('#partidas_json').val(JSON.stringify(partidas));
            
            // Confirmar antes de guardar
            Swal.fire({
                title: '¿Guardar cotización?',
                html: `Se guardará la cotización con <strong>${partidas.length}</strong> partida(s)<br>Total: <strong>$${formatearNumero(calcularTotales().total)}</strong>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-save me-2"></i>Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar formulario
                    this.submit();
                }
            });
        }
    });
    
    // Limpiar modal al cerrarlo
    $('#modalAgregarPartida').on('hidden.bs.modal', function () {
        limpiarFormularioPartida();
    });
    
    // Calcular precio al cambiar cantidad o precio unitario
    $('#cantidadArticulo, #precioUnitario, #porcentajeIVA').on('input', function() {
        const cantidad = parseFloat($('#cantidadArticulo').val()) || 0;
        const precioUnitario = parseFloat($('#precioUnitario').val()) || 0;
        const porcentajeIVA = parseFloat($('#porcentajeIVA').val()) || 0;
        
        const precioTotal = cantidad * precioUnitario;
        const montoIVA = precioTotal * (porcentajeIVA / 100);
        const total = precioTotal + montoIVA;
        
        console.log('Vista previa:', {
            subtotal: precioTotal.toFixed(2),
            iva: montoIVA.toFixed(2),
            total: total.toFixed(2)
        });
    });
    
    // Buscar artículo al presionar Enter en el campo de código
    $('#codigoArticulo').on('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            buscarArticulo();
        }
    });
    
    // Atajos de teclado en el modal
    $(document).on('keydown', '#modalAgregarPartida', function(e) {
        // Enter para guardar (excepto en textarea)
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && e.target.id !== 'codigoArticulo') {
            e.preventDefault();
            guardarPartida();
        }
        
        // Escape para cerrar
        if (e.key === 'Escape') {
            $('#modalAgregarPartida').modal('hide');
        }
    });
});

/**
 * Inicializar DataTable
 */
function inicializarDataTable() {
    tablaPartidas = $('#tablaPartidas').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        data: partidas,
        columns: [
            { 
                data: null,
                render: function (data, type, row, meta) {
                    return `<span class="badge badge-partida bg-primary">${meta.row + 1}</span>`;
                },
                width: '40px'
            },
            { 
                data: 'codigoarticulo',
                width: '100px'
            },
            { 
                data: 'descripcion',
                width: '250px'
            },
            { 
                data: 'unidad_medida',
                width: '80px',
                className: 'text-center'
            },
            { 
                data: 'cantidad',
                render: function(data) {
                    return parseFloat(data).toFixed(2);
                },
                className: 'text-end',
                width: '100px'
            },
            { 
                data: 'precio_unitario',
                render: function(data) {
                    return '$' + formatearNumero(parseFloat(data));
                },
                className: 'text-end',
                width: '120px'
            },
            { 
                data: 'precio_total',
                render: function(data) {
                    return '<strong>$' + formatearNumero(parseFloat(data)) + '</strong>';
                },
                className: 'text-end',
                width: '120px'
            },
            { 
                data: 'porcentaje_iva',
                render: function(data) {
                    let color = 'info';
                    if (data == 0) color = 'secondary';
                    if (data == 16) color = 'success';
                    return `<span class="badge bg-${color}">${data}%</span>`;
                },
                className: 'text-center',
                width: '80px'
            },
            { 
                data: 'monto_iva',
                render: function(data) {
                    return '$' + formatearNumero(parseFloat(data));
                },
                className: 'text-end',
                width: '120px'
            },
            { 
                data: 'total_partida',
                render: function(data) {
                    return '<strong class="text-primary">$' + formatearNumero(parseFloat(data)) + '</strong>';
                },
                className: 'text-end',
                width: '140px'
            },
            { 
                data: 'dias_entrega',
                render: function(data) {
                    return `<span class="badge bg-info">${data} días</span>`;
                },
                className: 'text-center',
                width: '100px'
            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    return `
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-warning btn-accion" 
                                    onclick="editarPartida(${meta.row})" 
                                    title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btn-accion" 
                                    onclick="eliminarPartida(${meta.row})" 
                                    title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                },
                orderable: false,
                className: 'text-center',
                width: '100px'
            }
        ],
        order: [[0, 'asc']],
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        drawCallback: function() {
            // Añadir animación al cargar filas
            $('#tablaPartidas tbody tr').addClass('animate__animated animate__fadeIn');
        }
    });
}

/**
 * Buscar artículo por código
 */
function buscarArticulo() {
    const codigo = $('#codigoArticulo').val().trim();
    
    if (codigo === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Ingrese un código de artículo'
        });
        $('#codigoArticulo').focus();
        return;
    }
    
    // Construir URL
    const baseUrl = window.location.origin;
    const pathname = window.location.pathname;
    const pathParts = pathname.split('/').filter(p => p);
    
    // Detectar si está en subdirectorio
    let ajaxUrl;
    if (pathParts[0] === 'sistema_mvc') {
        ajaxUrl = baseUrl + '/sistema_mvc/cotizaciones/obtenerArticulo';
    } else {
        ajaxUrl = baseUrl + '/cotizaciones/obtenerArticulo';
    }
    
    console.log('🔍 Buscando artículo:', codigo);
    console.log('📍 URL AJAX:', ajaxUrl);
    
    // Mostrar loading
    Swal.fire({
        title: 'Buscando...',
        html: 'Buscando artículo en el inventario',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: ajaxUrl,
        type: 'POST',
        dataType: 'json',
        data: { codigoarticulo: codigo },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            console.log('📤 Enviando solicitud...');
        },
        success: function(response) {
            console.log('✅ Respuesta recibida:', response);
            Swal.close();
            
            if (response.success) {
                // Llenar datos del artículo
                $('#descripcionArticulo').val(response.articulo.descripcion);
                $('#unidadArticulo').val(response.articulo.unidad);
                
                // Llenar precio de venta automáticamente
                $('#precioUnitario').val(response.articulo.precio_venta);
                
                // Mostrar información adicional en consola
                console.log('💰 Artículo cargado:');
                console.log('   - Precio Costo:  else {
                Swal.fire({
                    icon: 'warning',
                    title: 'No encontrado',
                    text: response.mensaje,
                    confirmButtonText: 'OK'
                });
                $('#codigoArticulo').focus().select();
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error AJAX:');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Status:', xhr.status);
            console.error('Response Text:', xhr.responseText);
            
            Swal.close();
            
            let mensajeError = 'Error al buscar el artículo en el servidor';
            
            if (xhr.status === 404) {
                mensajeError = 'La ruta del controlador no existe (Error 404)';
            } else if (xhr.status === 500) {
                mensajeError = 'Error interno del servidor (Error 500)';
            } else if (xhr.status === 0) {
                mensajeError = 'No se pudo conectar con el servidor';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error en la búsqueda',
                html: `<strong>${mensajeError}</strong><br><br>
                       <small>Status: ${xhr.status}<br>
                       Revisa la consola (F12) para más detalles</small>`,
                confirmButtonText: 'OK'
            });
        }
    });
}

/**
 * Guardar partida (agregar o editar)
 */
function guardarPartida() {
    // Validar formulario
    const codigo = $('#codigoArticulo').val().trim();
    const descripcion = $('#descripcionArticulo').val().trim();
    const cantidad = parseFloat($('#cantidadArticulo').val()) || 0;
    const precioUnitario = parseFloat($('#precioUnitario').val()) || 0;
    
    // Validaciones
    if (!codigo) {
        Swal.fire({
            icon: 'error',
            title: 'Código requerido',
            text: 'Ingrese el código del artículo'
        });
        $('#codigoArticulo').focus();
        return;
    }
    
    if (!descripcion) {
        Swal.fire({
            icon: 'error',
            title: 'Descripción requerida',
            text: 'Ingrese la descripción del artículo'
        });
        $('#descripcionArticulo').focus();
        return;
    }
    
    if (cantidad <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Cantidad inválida',
            text: 'La cantidad debe ser mayor a 0'
        });
        $('#cantidadArticulo').focus();
        return;
    }
    
    if (precioUnitario <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Precio inválido',
            text: 'El precio unitario debe ser mayor a 0'
        });
        $('#precioUnitario').focus();
        return;
    }
    
    // Calcular totales
    const unidad = $('#unidadArticulo').val();
    const porcentajeIVA = parseFloat($('#porcentajeIVA').val()) || 0;
    const diasEntrega = parseInt($('#diasEntregaArticulo').val());
    
    const precioTotal = cantidad * precioUnitario;
    const montoIVA = precioTotal * (porcentajeIVA / 100);
    const totalPartida = precioTotal + montoIVA;
    
    const partida = {
        codigoarticulo: codigo,
        descripcion: descripcion,
        cantidad: cantidad,
        precio_unitario: precioUnitario,
        precio_total: precioTotal,
        dias_entrega: diasEntrega,
        unidad_medida: unidad,
        porcentaje_iva: porcentajeIVA,
        monto_iva: montoIVA,
        subtotal_partida: precioTotal,
        total_partida: totalPartida,
        observaciones_partida: $('#observacionesPartida').val().trim()
    };
    
    const index = parseInt($('#partidaIndex').val());
    
    if (index >= 0) {
        // Editar partida existente
        partidas[index] = partida;
        
        Swal.fire({
            icon: 'success',
            title: 'Partida actualizada',
            text: 'Los cambios han sido guardados',
            timer: 1500,
            showConfirmButton: false
        });
    } else {
        // Agregar nueva partida
        partidas.push(partida);
        contadorPartidas++;
        
        Swal.fire({
            icon: 'success',
            title: 'Partida agregada',
            text: `Se agregó la partida #${contadorPartidas}`,
            timer: 1500,
            showConfirmButton: false
        });
    }
    
    // Actualizar DataTable
    actualizarDataTable();
    
    // Actualizar totales
    actualizarTotales();
    
    // Cerrar modal
    $('#modalAgregarPartida').modal('hide');
    
    // Limpiar formulario
    limpiarFormularioPartida();
}

/**
 * Editar una partida
 */
function editarPartida(index) {
    editandoIndex = index;
    const partida = partidas[index];
    
    // Cambiar título del modal
    $('#tituloModal').html('<i class="fas fa-edit me-2"></i>Editar Partida #' + (index + 1));
    
    // Llenar formulario con datos de la partida
    $('#partidaIndex').val(index);
    $('#codigoArticulo').val(partida.codigoarticulo);
    $('#descripcionArticulo').val(partida.descripcion);
    $('#cantidadArticulo').val(partida.cantidad);
    $('#unidadArticulo').val(partida.unidad_medida);
    $('#precioUnitario').val(partida.precio_unitario);
    $('#porcentajeIVA').val(partida.porcentaje_iva);
    $('#diasEntregaArticulo').val(partida.dias_entrega);
    
    // Abrir modal
    $('#modalAgregarPartida').modal('show');
    
    // Focus en cantidad
    setTimeout(function() {
        $('#cantidadArticulo').focus().select();
    }, 500);
}

/**
 * Eliminar una partida
 */
function eliminarPartida(index) {
    const partida = partidas[index];
    
    Swal.fire({
        title: '¿Eliminar partida?',
        html: `<div class="text-start">
                <strong>Código:</strong> ${partida.codigoarticulo}<br>
                <strong>Descripción:</strong> ${partida.descripcion}<br>
                <strong>Total:</strong> $${formatearNumero(partida.total_partida)}
               </div><br>
               <p class="text-danger mb-0">Esta acción no se puede deshacer</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Eliminar del array
            partidas.splice(index, 1);
            
            // Actualizar DataTable
            actualizarDataTable();
            
            // Actualizar totales
            actualizarTotales();
            
            Swal.fire({
                icon: 'success',
                title: 'Eliminada',
                text: 'La partida ha sido eliminada correctamente',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

/**
 * Actualizar DataTable
 */
function actualizarDataTable() {
    tablaPartidas.clear();
    tablaPartidas.rows.add(partidas);
    tablaPartidas.draw();
    
    // Actualizar contador
    $('#contadorPartidas').text(partidas.length);
}

/**
 * Calcular totales generales
 */
function calcularTotales() {
    let subtotal = 0;
    let totalIVA = 0;
    
    partidas.forEach(function(partida) {
        subtotal += parseFloat(partida.precio_total);
        totalIVA += parseFloat(partida.monto_iva);
    });
    
    const total = subtotal + totalIVA;
    
    return {
        subtotal: subtotal,
        iva: totalIVA,
        total: total
    };
}

/**
 * Actualizar visualización de totales
 */
function actualizarTotales() {
    const totales = calcularTotales();
    
    // Actualizar displays con animación
    $('#displaySubtotal').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.subtotal)).fadeIn(200);
    });
    
    $('#displayIVA').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.iva)).fadeIn(200);
    });
    
    $('#displayTotal').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.total)).fadeIn(200);
    });
    
    // Actualizar campos ocultos
    $('#subtotal').val(totales.subtotal.toFixed(2));
    $('#iva').val(totales.iva.toFixed(2));
    $('#total').val(totales.total.toFixed(2));
}

/**
 * Limpiar formulario de partida
 */
function limpiarFormularioPartida() {
    $('#formPartida')[0].reset();
    $('#partidaIndex').val('-1');
    $('#cantidadArticulo').val('1');
    $('#precioUnitario').val('0');
    $('#porcentajeIVA').val('16');
    $('#diasEntregaArticulo').val('7');
    $('#unidadArticulo').val('PZA');
    $('#tituloModal').html('<i class="fas fa-plus-circle me-2"></i>Agregar Partida');
    editandoIndex = -1;
}

/**
 * Validar formulario principal
 */
function validarFormulario() {
    const clientesid = $('#clientesid').val();
    const fecha = $('#fecha').val();
    const condiciones = $('#condiciones').val();
    
    if (!clientesid) {
        Swal.fire({
            icon: 'error',
            title: 'Cliente requerido',
            text: 'Debe seleccionar un cliente',
            confirmButtonText: 'OK'
        });
        $('#clientesid').focus();
        return false;
    }
    
    if (!fecha) {
        Swal.fire({
            icon: 'error',
            title: 'Fecha requerida',
            text: 'Debe especificar la fecha de la cotización',
            confirmButtonText: 'OK'
        });
        $('#fecha').focus();
        return false;
    }
    
    if (!condiciones) {
        Swal.fire({
            icon: 'error',
            title: 'Condiciones requeridas',
            text: 'Debe especificar las condiciones de pago',
            confirmButtonText: 'OK'
        });
        $('#condiciones').focus();
        return false;
    }
    
    return true;
}

/**
 * Formatear número con comas y decimales
 */
function formatearNumero(numero) {
    return parseFloat(numero).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Log de carga exitosa
console.log('✅ cotizaciones_datatable.js cargado completamente'); + response.articulo.precio_costo);
                console.log('   - Precio Venta:  else {
                Swal.fire({
                    icon: 'warning',
                    title: 'No encontrado',
                    text: response.mensaje,
                    confirmButtonText: 'OK'
                });
                $('#codigoArticulo').focus().select();
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error AJAX:');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Status:', xhr.status);
            console.error('Response Text:', xhr.responseText);
            
            Swal.close();
            
            let mensajeError = 'Error al buscar el artículo en el servidor';
            
            if (xhr.status === 404) {
                mensajeError = 'La ruta del controlador no existe (Error 404)';
            } else if (xhr.status === 500) {
                mensajeError = 'Error interno del servidor (Error 500)';
            } else if (xhr.status === 0) {
                mensajeError = 'No se pudo conectar con el servidor';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error en la búsqueda',
                html: `<strong>${mensajeError}</strong><br><br>
                       <small>Status: ${xhr.status}<br>
                       Revisa la consola (F12) para más detalles</small>`,
                confirmButtonText: 'OK'
            });
        }
    });
}

/**
 * Guardar partida (agregar o editar)
 */
function guardarPartida() {
    // Validar formulario
    const codigo = $('#codigoArticulo').val().trim();
    const descripcion = $('#descripcionArticulo').val().trim();
    const cantidad = parseFloat($('#cantidadArticulo').val()) || 0;
    const precioUnitario = parseFloat($('#precioUnitario').val()) || 0;
    
    // Validaciones
    if (!codigo) {
        Swal.fire({
            icon: 'error',
            title: 'Código requerido',
            text: 'Ingrese el código del artículo'
        });
        $('#codigoArticulo').focus();
        return;
    }
    
    if (!descripcion) {
        Swal.fire({
            icon: 'error',
            title: 'Descripción requerida',
            text: 'Ingrese la descripción del artículo'
        });
        $('#descripcionArticulo').focus();
        return;
    }
    
    if (cantidad <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Cantidad inválida',
            text: 'La cantidad debe ser mayor a 0'
        });
        $('#cantidadArticulo').focus();
        return;
    }
    
    if (precioUnitario <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Precio inválido',
            text: 'El precio unitario debe ser mayor a 0'
        });
        $('#precioUnitario').focus();
        return;
    }
    
    // Calcular totales
    const unidad = $('#unidadArticulo').val();
    const porcentajeIVA = parseFloat($('#porcentajeIVA').val()) || 0;
    const diasEntrega = parseInt($('#diasEntregaArticulo').val());
    
    const precioTotal = cantidad * precioUnitario;
    const montoIVA = precioTotal * (porcentajeIVA / 100);
    const totalPartida = precioTotal + montoIVA;
    
    const partida = {
        codigoarticulo: codigo,
        descripcion: descripcion,
        cantidad: cantidad,
        precio_unitario: precioUnitario,
        precio_total: precioTotal,
        dias_entrega: diasEntrega,
        unidad_medida: unidad,
        porcentaje_iva: porcentajeIVA,
        monto_iva: montoIVA,
        subtotal_partida: precioTotal,
        total_partida: totalPartida
    };
    
    const index = parseInt($('#partidaIndex').val());
    
    if (index >= 0) {
        // Editar partida existente
        partidas[index] = partida;
        
        Swal.fire({
            icon: 'success',
            title: 'Partida actualizada',
            text: 'Los cambios han sido guardados',
            timer: 1500,
            showConfirmButton: false
        });
    } else {
        // Agregar nueva partida
        partidas.push(partida);
        contadorPartidas++;
        
        Swal.fire({
            icon: 'success',
            title: 'Partida agregada',
            text: `Se agregó la partida #${contadorPartidas}`,
            timer: 1500,
            showConfirmButton: false
        });
    }
    
    // Actualizar DataTable
    actualizarDataTable();
    
    // Actualizar totales
    actualizarTotales();
    
    // Cerrar modal
    $('#modalAgregarPartida').modal('hide');
    
    // Limpiar formulario
    limpiarFormularioPartida();
}

/**
 * Editar una partida
 */
function editarPartida(index) {
    editandoIndex = index;
    const partida = partidas[index];
    
    // Cambiar título del modal
    $('#tituloModal').html('<i class="fas fa-edit me-2"></i>Editar Partida #' + (index + 1));
    
    // Llenar formulario con datos de la partida
    $('#partidaIndex').val(index);
    $('#codigoArticulo').val(partida.codigoarticulo);
    $('#descripcionArticulo').val(partida.descripcion);
    $('#cantidadArticulo').val(partida.cantidad);
    $('#unidadArticulo').val(partida.unidad_medida);
    $('#precioUnitario').val(partida.precio_unitario);
    $('#porcentajeIVA').val(partida.porcentaje_iva);
    $('#diasEntregaArticulo').val(partida.dias_entrega);
    
    // Abrir modal
    $('#modalAgregarPartida').modal('show');
    
    // Focus en cantidad
    setTimeout(function() {
        $('#cantidadArticulo').focus().select();
    }, 500);
}

/**
 * Eliminar una partida
 */
function eliminarPartida(index) {
    const partida = partidas[index];
    
    Swal.fire({
        title: '¿Eliminar partida?',
        html: `<div class="text-start">
                <strong>Código:</strong> ${partida.codigoarticulo}<br>
                <strong>Descripción:</strong> ${partida.descripcion}<br>
                <strong>Total:</strong> $${formatearNumero(partida.total_partida)}
               </div><br>
               <p class="text-danger mb-0">Esta acción no se puede deshacer</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Eliminar del array
            partidas.splice(index, 1);
            
            // Actualizar DataTable
            actualizarDataTable();
            
            // Actualizar totales
            actualizarTotales();
            
            Swal.fire({
                icon: 'success',
                title: 'Eliminada',
                text: 'La partida ha sido eliminada correctamente',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

/**
 * Actualizar DataTable
 */
function actualizarDataTable() {
    tablaPartidas.clear();
    tablaPartidas.rows.add(partidas);
    tablaPartidas.draw();
    
    // Actualizar contador
    $('#contadorPartidas').text(partidas.length);
}

/**
 * Calcular totales generales
 */
function calcularTotales() {
    let subtotal = 0;
    let totalIVA = 0;
    
    partidas.forEach(function(partida) {
        subtotal += parseFloat(partida.precio_total);
        totalIVA += parseFloat(partida.monto_iva);
    });
    
    const total = subtotal + totalIVA;
    
    return {
        subtotal: subtotal,
        iva: totalIVA,
        total: total
    };
}

/**
 * Actualizar visualización de totales
 */
function actualizarTotales() {
    const totales = calcularTotales();
    
    // Actualizar displays con animación
    $('#displaySubtotal').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.subtotal)).fadeIn(200);
    });
    
    $('#displayIVA').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.iva)).fadeIn(200);
    });
    
    $('#displayTotal').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.total)).fadeIn(200);
    });
    
    // Actualizar campos ocultos
    $('#subtotal').val(totales.subtotal.toFixed(2));
    $('#iva').val(totales.iva.toFixed(2));
    $('#total').val(totales.total.toFixed(2));
}

/**
 * Limpiar formulario de partida
 */
function limpiarFormularioPartida() {
    $('#formPartida')[0].reset();
    $('#partidaIndex').val('-1');
    $('#cantidadArticulo').val('1');
    $('#precioUnitario').val('0');
    $('#porcentajeIVA').val('16');
    $('#diasEntregaArticulo').val('7');
    $('#unidadArticulo').val('PZA');
    $('#tituloModal').html('<i class="fas fa-plus-circle me-2"></i>Agregar Partida');
    editandoIndex = -1;
}

/**
 * Validar formulario principal
 */
function validarFormulario() {
    const clientesid = $('#clientesid').val();
    const fecha = $('#fecha').val();
    const condiciones = $('#condiciones').val();
    
    if (!clientesid) {
        Swal.fire({
            icon: 'error',
            title: 'Cliente requerido',
            text: 'Debe seleccionar un cliente',
            confirmButtonText: 'OK'
        });
        $('#clientesid').focus();
        return false;
    }
    
    if (!fecha) {
        Swal.fire({
            icon: 'error',
            title: 'Fecha requerida',
            text: 'Debe especificar la fecha de la cotización',
            confirmButtonText: 'OK'
        });
        $('#fecha').focus();
        return false;
    }
    
    if (!condiciones) {
        Swal.fire({
            icon: 'error',
            title: 'Condiciones requeridas',
            text: 'Debe especificar las condiciones de pago',
            confirmButtonText: 'OK'
        });
        $('#condiciones').focus();
        return false;
    }
    
    return true;
}

/**
 * Formatear número con comas y decimales
 */
function formatearNumero(numero) {
    return parseFloat(numero).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Log de carga exitosa
console.log('✅ cotizaciones_datatable.js cargado completamente'); + response.articulo.precio_venta);
                console.log('   - Moneda: ' + response.articulo.moneda);
                console.log('   - Existencia: ' + response.articulo.existencia);
                
                // Focus en cantidad
                $('#cantidadArticulo').focus().select();
                
                // Mensaje más informativo
                let mensaje = response.articulo.descripcion;
                if (response.articulo.precio_venta > 0) {
                    mensaje += '\nPrecio:  else {
                Swal.fire({
                    icon: 'warning',
                    title: 'No encontrado',
                    text: response.mensaje,
                    confirmButtonText: 'OK'
                });
                $('#codigoArticulo').focus().select();
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error AJAX:');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Status:', xhr.status);
            console.error('Response Text:', xhr.responseText);
            
            Swal.close();
            
            let mensajeError = 'Error al buscar el artículo en el servidor';
            
            if (xhr.status === 404) {
                mensajeError = 'La ruta del controlador no existe (Error 404)';
            } else if (xhr.status === 500) {
                mensajeError = 'Error interno del servidor (Error 500)';
            } else if (xhr.status === 0) {
                mensajeError = 'No se pudo conectar con el servidor';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error en la búsqueda',
                html: `<strong>${mensajeError}</strong><br><br>
                       <small>Status: ${xhr.status}<br>
                       Revisa la consola (F12) para más detalles</small>`,
                confirmButtonText: 'OK'
            });
        }
    });
}

/**
 * Guardar partida (agregar o editar)
 */
function guardarPartida() {
    // Validar formulario
    const codigo = $('#codigoArticulo').val().trim();
    const descripcion = $('#descripcionArticulo').val().trim();
    const cantidad = parseFloat($('#cantidadArticulo').val()) || 0;
    const precioUnitario = parseFloat($('#precioUnitario').val()) || 0;
    
    // Validaciones
    if (!codigo) {
        Swal.fire({
            icon: 'error',
            title: 'Código requerido',
            text: 'Ingrese el código del artículo'
        });
        $('#codigoArticulo').focus();
        return;
    }
    
    if (!descripcion) {
        Swal.fire({
            icon: 'error',
            title: 'Descripción requerida',
            text: 'Ingrese la descripción del artículo'
        });
        $('#descripcionArticulo').focus();
        return;
    }
    
    if (cantidad <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Cantidad inválida',
            text: 'La cantidad debe ser mayor a 0'
        });
        $('#cantidadArticulo').focus();
        return;
    }
    
    if (precioUnitario <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Precio inválido',
            text: 'El precio unitario debe ser mayor a 0'
        });
        $('#precioUnitario').focus();
        return;
    }
    
    // Calcular totales
    const unidad = $('#unidadArticulo').val();
    const porcentajeIVA = parseFloat($('#porcentajeIVA').val()) || 0;
    const diasEntrega = parseInt($('#diasEntregaArticulo').val());
    
    const precioTotal = cantidad * precioUnitario;
    const montoIVA = precioTotal * (porcentajeIVA / 100);
    const totalPartida = precioTotal + montoIVA;
    
    const partida = {
        codigoarticulo: codigo,
        descripcion: descripcion,
        cantidad: cantidad,
        precio_unitario: precioUnitario,
        precio_total: precioTotal,
        dias_entrega: diasEntrega,
        unidad_medida: unidad,
        porcentaje_iva: porcentajeIVA,
        monto_iva: montoIVA,
        subtotal_partida: precioTotal,
        total_partida: totalPartida
    };
    
    const index = parseInt($('#partidaIndex').val());
    
    if (index >= 0) {
        // Editar partida existente
        partidas[index] = partida;
        
        Swal.fire({
            icon: 'success',
            title: 'Partida actualizada',
            text: 'Los cambios han sido guardados',
            timer: 1500,
            showConfirmButton: false
        });
    } else {
        // Agregar nueva partida
        partidas.push(partida);
        contadorPartidas++;
        
        Swal.fire({
            icon: 'success',
            title: 'Partida agregada',
            text: `Se agregó la partida #${contadorPartidas}`,
            timer: 1500,
            showConfirmButton: false
        });
    }
    
    // Actualizar DataTable
    actualizarDataTable();
    
    // Actualizar totales
    actualizarTotales();
    
    // Cerrar modal
    $('#modalAgregarPartida').modal('hide');
    
    // Limpiar formulario
    limpiarFormularioPartida();
}

/**
 * Editar una partida
 */
function editarPartida(index) {
    editandoIndex = index;
    const partida = partidas[index];
    
    // Cambiar título del modal
    $('#tituloModal').html('<i class="fas fa-edit me-2"></i>Editar Partida #' + (index + 1));
    
    // Llenar formulario con datos de la partida
    $('#partidaIndex').val(index);
    $('#codigoArticulo').val(partida.codigoarticulo);
    $('#descripcionArticulo').val(partida.descripcion);
    $('#cantidadArticulo').val(partida.cantidad);
    $('#unidadArticulo').val(partida.unidad_medida);
    $('#precioUnitario').val(partida.precio_unitario);
    $('#porcentajeIVA').val(partida.porcentaje_iva);
    $('#diasEntregaArticulo').val(partida.dias_entrega);
    
    // Abrir modal
    $('#modalAgregarPartida').modal('show');
    
    // Focus en cantidad
    setTimeout(function() {
        $('#cantidadArticulo').focus().select();
    }, 500);
}

/**
 * Eliminar una partida
 */
function eliminarPartida(index) {
    const partida = partidas[index];
    
    Swal.fire({
        title: '¿Eliminar partida?',
        html: `<div class="text-start">
                <strong>Código:</strong> ${partida.codigoarticulo}<br>
                <strong>Descripción:</strong> ${partida.descripcion}<br>
                <strong>Total:</strong> $${formatearNumero(partida.total_partida)}
               </div><br>
               <p class="text-danger mb-0">Esta acción no se puede deshacer</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Eliminar del array
            partidas.splice(index, 1);
            
            // Actualizar DataTable
            actualizarDataTable();
            
            // Actualizar totales
            actualizarTotales();
            
            Swal.fire({
                icon: 'success',
                title: 'Eliminada',
                text: 'La partida ha sido eliminada correctamente',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

/**
 * Actualizar DataTable
 */
function actualizarDataTable() {
    tablaPartidas.clear();
    tablaPartidas.rows.add(partidas);
    tablaPartidas.draw();
    
    // Actualizar contador
    $('#contadorPartidas').text(partidas.length);
}

/**
 * Calcular totales generales
 */
function calcularTotales() {
    let subtotal = 0;
    let totalIVA = 0;
    
    partidas.forEach(function(partida) {
        subtotal += parseFloat(partida.precio_total);
        totalIVA += parseFloat(partida.monto_iva);
    });
    
    const total = subtotal + totalIVA;
    
    return {
        subtotal: subtotal,
        iva: totalIVA,
        total: total
    };
}

/**
 * Actualizar visualización de totales
 */
function actualizarTotales() {
    const totales = calcularTotales();
    
    // Actualizar displays con animación
    $('#displaySubtotal').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.subtotal)).fadeIn(200);
    });
    
    $('#displayIVA').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.iva)).fadeIn(200);
    });
    
    $('#displayTotal').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.total)).fadeIn(200);
    });
    
    // Actualizar campos ocultos
    $('#subtotal').val(totales.subtotal.toFixed(2));
    $('#iva').val(totales.iva.toFixed(2));
    $('#total').val(totales.total.toFixed(2));
}

/**
 * Limpiar formulario de partida
 */
function limpiarFormularioPartida() {
    $('#formPartida')[0].reset();
    $('#partidaIndex').val('-1');
    $('#cantidadArticulo').val('1');
    $('#precioUnitario').val('0');
    $('#porcentajeIVA').val('16');
    $('#diasEntregaArticulo').val('7');
    $('#unidadArticulo').val('PZA');
    $('#tituloModal').html('<i class="fas fa-plus-circle me-2"></i>Agregar Partida');
    editandoIndex = -1;
}

/**
 * Validar formulario principal
 */
function validarFormulario() {
    const clientesid = $('#clientesid').val();
    const fecha = $('#fecha').val();
    const condiciones = $('#condiciones').val();
    
    if (!clientesid) {
        Swal.fire({
            icon: 'error',
            title: 'Cliente requerido',
            text: 'Debe seleccionar un cliente',
            confirmButtonText: 'OK'
        });
        $('#clientesid').focus();
        return false;
    }
    
    if (!fecha) {
        Swal.fire({
            icon: 'error',
            title: 'Fecha requerida',
            text: 'Debe especificar la fecha de la cotización',
            confirmButtonText: 'OK'
        });
        $('#fecha').focus();
        return false;
    }
    
    if (!condiciones) {
        Swal.fire({
            icon: 'error',
            title: 'Condiciones requeridas',
            text: 'Debe especificar las condiciones de pago',
            confirmButtonText: 'OK'
        });
        $('#condiciones').focus();
        return false;
    }
    
    return true;
}

/**
 * Formatear número con comas y decimales
 */
function formatearNumero(numero) {
    return parseFloat(numero).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Log de carga exitosa
console.log('✅ cotizaciones_datatable.js cargado completamente'); + formatearNumero(response.articulo.precio_venta);
                }
                if (response.articulo.existencia > 0) {
                    mensaje += '\nExistencia: ' + response.articulo.existencia;
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'Artículo encontrado',
                    html: mensaje.replace(/\n/g, '<br>'),
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'No encontrado',
                    text: response.mensaje,
                    confirmButtonText: 'OK'
                });
                $('#codigoArticulo').focus().select();
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error AJAX:');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Status:', xhr.status);
            console.error('Response Text:', xhr.responseText);
            
            Swal.close();
            
            let mensajeError = 'Error al buscar el artículo en el servidor';
            
            if (xhr.status === 404) {
                mensajeError = 'La ruta del controlador no existe (Error 404)';
            } else if (xhr.status === 500) {
                mensajeError = 'Error interno del servidor (Error 500)';
            } else if (xhr.status === 0) {
                mensajeError = 'No se pudo conectar con el servidor';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error en la búsqueda',
                html: `<strong>${mensajeError}</strong><br><br>
                       <small>Status: ${xhr.status}<br>
                       Revisa la consola (F12) para más detalles</small>`,
                confirmButtonText: 'OK'
            });
        }
    });
}

/**
 * Guardar partida (agregar o editar)
 */
function guardarPartida() {
    // Validar formulario
    const codigo = $('#codigoArticulo').val().trim();
    const descripcion = $('#descripcionArticulo').val().trim();
    const cantidad = parseFloat($('#cantidadArticulo').val()) || 0;
    const precioUnitario = parseFloat($('#precioUnitario').val()) || 0;
    
    // Validaciones
    if (!codigo) {
        Swal.fire({
            icon: 'error',
            title: 'Código requerido',
            text: 'Ingrese el código del artículo'
        });
        $('#codigoArticulo').focus();
        return;
    }
    
    if (!descripcion) {
        Swal.fire({
            icon: 'error',
            title: 'Descripción requerida',
            text: 'Ingrese la descripción del artículo'
        });
        $('#descripcionArticulo').focus();
        return;
    }
    
    if (cantidad <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Cantidad inválida',
            text: 'La cantidad debe ser mayor a 0'
        });
        $('#cantidadArticulo').focus();
        return;
    }
    
    if (precioUnitario <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Precio inválido',
            text: 'El precio unitario debe ser mayor a 0'
        });
        $('#precioUnitario').focus();
        return;
    }
    
    // Calcular totales
    const unidad = $('#unidadArticulo').val();
    const porcentajeIVA = parseFloat($('#porcentajeIVA').val()) || 0;
    const diasEntrega = parseInt($('#diasEntregaArticulo').val());
    
    const precioTotal = cantidad * precioUnitario;
    const montoIVA = precioTotal * (porcentajeIVA / 100);
    const totalPartida = precioTotal + montoIVA;
    
    const partida = {
        codigoarticulo: codigo,
        descripcion: descripcion,
        cantidad: cantidad,
        precio_unitario: precioUnitario,
        precio_total: precioTotal,
        dias_entrega: diasEntrega,
        unidad_medida: unidad,
        porcentaje_iva: porcentajeIVA,
        monto_iva: montoIVA,
        subtotal_partida: precioTotal,
        total_partida: totalPartida
    };
    
    const index = parseInt($('#partidaIndex').val());
    
    if (index >= 0) {
        // Editar partida existente
        partidas[index] = partida;
        
        Swal.fire({
            icon: 'success',
            title: 'Partida actualizada',
            text: 'Los cambios han sido guardados',
            timer: 1500,
            showConfirmButton: false
        });
    } else {
        // Agregar nueva partida
        partidas.push(partida);
        contadorPartidas++;
        
        Swal.fire({
            icon: 'success',
            title: 'Partida agregada',
            text: `Se agregó la partida #${contadorPartidas}`,
            timer: 1500,
            showConfirmButton: false
        });
    }
    
    // Actualizar DataTable
    actualizarDataTable();
    
    // Actualizar totales
    actualizarTotales();
    
    // Cerrar modal
    $('#modalAgregarPartida').modal('hide');
    
    // Limpiar formulario
    limpiarFormularioPartida();
}

/**
 * Editar una partida
 */
function editarPartida(index) {
    editandoIndex = index;
    const partida = partidas[index];
    
    // Cambiar título del modal
    $('#tituloModal').html('<i class="fas fa-edit me-2"></i>Editar Partida #' + (index + 1));
    
    // Llenar formulario con datos de la partida
    $('#partidaIndex').val(index);
    $('#codigoArticulo').val(partida.codigoarticulo);
    $('#descripcionArticulo').val(partida.descripcion);
    $('#cantidadArticulo').val(partida.cantidad);
    $('#unidadArticulo').val(partida.unidad_medida);
    $('#precioUnitario').val(partida.precio_unitario);
    $('#porcentajeIVA').val(partida.porcentaje_iva);
    $('#diasEntregaArticulo').val(partida.dias_entrega);
    
    // Abrir modal
    $('#modalAgregarPartida').modal('show');
    
    // Focus en cantidad
    setTimeout(function() {
        $('#cantidadArticulo').focus().select();
    }, 500);
}

/**
 * Eliminar una partida
 */
function eliminarPartida(index) {
    const partida = partidas[index];
    
    Swal.fire({
        title: '¿Eliminar partida?',
        html: `<div class="text-start">
                <strong>Código:</strong> ${partida.codigoarticulo}<br>
                <strong>Descripción:</strong> ${partida.descripcion}<br>
                <strong>Total:</strong> $${formatearNumero(partida.total_partida)}
               </div><br>
               <p class="text-danger mb-0">Esta acción no se puede deshacer</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Eliminar del array
            partidas.splice(index, 1);
            
            // Actualizar DataTable
            actualizarDataTable();
            
            // Actualizar totales
            actualizarTotales();
            
            Swal.fire({
                icon: 'success',
                title: 'Eliminada',
                text: 'La partida ha sido eliminada correctamente',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

/**
 * Actualizar DataTable
 */
function actualizarDataTable() {
    tablaPartidas.clear();
    tablaPartidas.rows.add(partidas);
    tablaPartidas.draw();
    
    // Actualizar contador
    $('#contadorPartidas').text(partidas.length);
}

/**
 * Calcular totales generales
 */
function calcularTotales() {
    let subtotal = 0;
    let totalIVA = 0;
    
    partidas.forEach(function(partida) {
        subtotal += parseFloat(partida.precio_total);
        totalIVA += parseFloat(partida.monto_iva);
    });
    
    const total = subtotal + totalIVA;
    
    return {
        subtotal: subtotal,
        iva: totalIVA,
        total: total
    };
}

/**
 * Actualizar visualización de totales
 */
function actualizarTotales() {
    const totales = calcularTotales();
    
    // Actualizar displays con animación
    $('#displaySubtotal').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.subtotal)).fadeIn(200);
    });
    
    $('#displayIVA').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.iva)).fadeIn(200);
    });
    
    $('#displayTotal').fadeOut(200, function() {
        $(this).text('$' + formatearNumero(totales.total)).fadeIn(200);
    });
    
    // Actualizar campos ocultos
    $('#subtotal').val(totales.subtotal.toFixed(2));
    $('#iva').val(totales.iva.toFixed(2));
    $('#total').val(totales.total.toFixed(2));
}

/**
 * Limpiar formulario de partida
 */
function limpiarFormularioPartida() {
    $('#formPartida')[0].reset();
    $('#partidaIndex').val('-1');
    $('#cantidadArticulo').val('1');
    $('#precioUnitario').val('0');
    $('#porcentajeIVA').val('16');
    $('#diasEntregaArticulo').val('7');
    $('#unidadArticulo').val('PZA');
    $('#tituloModal').html('<i class="fas fa-plus-circle me-2"></i>Agregar Partida');
    editandoIndex = -1;
}

/**
 * Validar formulario principal
 */
function validarFormulario() {
    const clientesid = $('#clientesid').val();
    const fecha = $('#fecha').val();
    const condiciones = $('#condiciones').val();
    
    if (!clientesid) {
        Swal.fire({
            icon: 'error',
            title: 'Cliente requerido',
            text: 'Debe seleccionar un cliente',
            confirmButtonText: 'OK'
        });
        $('#clientesid').focus();
        return false;
    }
    
    if (!fecha) {
        Swal.fire({
            icon: 'error',
            title: 'Fecha requerida',
            text: 'Debe especificar la fecha de la cotización',
            confirmButtonText: 'OK'
        });
        $('#fecha').focus();
        return false;
    }
    
    if (!condiciones) {
        Swal.fire({
            icon: 'error',
            title: 'Condiciones requeridas',
            text: 'Debe especificar las condiciones de pago',
            confirmButtonText: 'OK'
        });
        $('#condiciones').focus();
        return false;
    }
    
    return true;
}

/**
 * Formatear número con comas y decimales
 */
function formatearNumero(numero) {
    return parseFloat(numero).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Log de carga exitosa
console.log('✅ cotizaciones_datatable.js cargado completamente');