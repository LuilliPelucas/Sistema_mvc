/**
 * JavaScript para el módulo de Cotizaciones
 * Maneja partidas dinámicas y cálculos automáticos
 * Versión corregida con validaciones
 */

let contadorPartidas = 0;

// Verificar que jQuery esté cargado
if (typeof jQuery === 'undefined') {
    console.error('ERROR: jQuery no está cargado. Verifique que se incluya antes de este script.');
}

$(document).ready(function() {
    console.log('Módulo de cotizaciones cargado');
    console.log('jQuery versión:', $.fn.jquery);
    
    // Verificar elementos necesarios
    if ($('#btnAgregarPartida').length === 0) {
        console.error('ERROR: No se encontró el botón #btnAgregarPartida');
    }
    
    if ($('#templatePartida').length === 0) {
        console.error('ERROR: No se encontró el template #templatePartida');
    }
    
    if ($('#contenedorPartidas').length === 0) {
        console.error('ERROR: No se encontró el contenedor #contenedorPartidas');
    }
    
    // Agregar primera partida automáticamente
    agregarPartida();
    
    // Botón agregar partida
    $('#btnAgregarPartida').on('click', function() {
        console.log('Botón Agregar Partida presionado');
        agregarPartida();
    });
    
    // Validar formulario antes de enviar
    $('#formCotizacion').on('submit', function(e) {
        e.preventDefault();
        
        if (validarFormulario()) {
            // Recopilar partidas
            const partidas = recopilarPartidas();
            
            if (partidas.length === 0) {
                Swal.fire('Error', 'Debe agregar al menos una partida', 'error');
                return false;
            }
            
            // Guardar partidas en campo oculto
            $('#partidas_json').val(JSON.stringify(partidas));
            
            console.log('Formulario válido, enviando...', partidas);
            
            // Enviar formulario
            this.submit();
        }
    });
});

/**
 * Agregar una nueva partida
 */
function agregarPartida() {
    contadorPartidas++;
    
    console.log('Agregando partida número:', contadorPartidas);
    
    // Verificar que el template existe
    const template = document.getElementById('templatePartida');
    if (!template) {
        console.error('ERROR: Template no encontrado');
        alert('Error: Template de partida no encontrado');
        return;
    }
    
    // Clonar template
    const clone = template.content.cloneNode(true);
    
    // Configurar el índice
    const partidaRow = clone.querySelector('.partida-row');
    partidaRow.setAttribute('data-partida-index', contadorPartidas);
    
    // Actualizar número de partida
    const numeroPartida = clone.querySelector('.partida-numero');
    numeroPartida.textContent = contadorPartidas;
    
    // Agregar al contenedor
    const contenedor = document.getElementById('contenedorPartidas');
    if (!contenedor) {
        console.error('ERROR: Contenedor no encontrado');
        alert('Error: Contenedor de partidas no encontrado');
        return;
    }
    
    contenedor.appendChild(clone);
    
    console.log('Partida agregada exitosamente:', contadorPartidas);
    
    // Mostrar notificación
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Partida agregada',
            text: `Partida #${contadorPartidas} agregada exitosamente`,
            timer: 1500,
            showConfirmButton: false
        });
    }
}

/**
 * Eliminar una partida
 */
function eliminarPartida(btn) {
    const partidaRow = btn.closest('.partida-row');
    
    Swal.fire({
        title: '¿Eliminar partida?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            partidaRow.remove();
            recalcularTotales();
            renumerarPartidas();
            
            Swal.fire({
                icon: 'success',
                title: 'Eliminada',
                text: 'La partida ha sido eliminada',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

/**
 * Buscar artículo por código (AJAX)
 */
function buscarArticulo(input) {
    const codigo = input.value.trim();
    
    if (codigo === '') return;
    
    const partidaRow = input.closest('.partida-row');
    
    console.log('Buscando artículo:', codigo);
    
    $.ajax({
        url: window.location.origin + '/sistema_mvc/cotizaciones/obtenerArticulo',
        type: 'POST',
        dataType: 'json',
        data: { codigoarticulo: codigo },
        beforeSend: function() {
            // Mostrar indicador de carga
            input.style.borderColor = '#ffc107';
        },
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            
            if (response.success) {
                // Llenar datos del artículo
                partidaRow.querySelector('.partida-descripcion').value = response.articulo.descripcion;
                partidaRow.querySelector('.partida-unidad').value = response.articulo.unidad;
                
                // Restaurar borde normal
                input.style.borderColor = '#28a745';
                
                // Focus en cantidad
                partidaRow.querySelector('.partida-cantidad').focus();
                
                // Mostrar mensaje de éxito
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Artículo encontrado',
                        text: response.articulo.descripcion,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            } else {
                input.style.borderColor = '#dc3545';
                Swal.fire('No encontrado', response.mensaje, 'warning');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al buscar artículo:', error);
            input.style.borderColor = '#dc3545';
            Swal.fire('Error', 'Error al buscar el artículo', 'error');
        }
    });
}

/**
 * Calcular totales de una partida
 */
function calcularPartida(input) {
    const partidaRow = input.closest('.partida-row');
    
    // Obtener valores
    const cantidad = parseFloat(partidaRow.querySelector('.partida-cantidad').value) || 0;
    const precioUnitario = parseFloat(partidaRow.querySelector('.partida-precio-unitario').value) || 0;
    const porcentajeIVA = parseFloat(partidaRow.querySelector('.partida-porcentaje-iva').value) || 0;
    
    console.log('Calculando partida:', { cantidad, precioUnitario, porcentajeIVA });
    
    // Calcular precio total
    const precioTotal = cantidad * precioUnitario;
    partidaRow.querySelector('.partida-precio-total').value = precioTotal.toFixed(2);
    
    // Calcular IVA
    const montoIVA = precioTotal * (porcentajeIVA / 100);
    partidaRow.querySelector('.partida-monto-iva').value = montoIVA.toFixed(2);
    
    // Recalcular totales generales
    recalcularTotales();
}

/**
 * Recalcular totales generales de la cotización
 */
function recalcularTotales() {
    let subtotal = 0;
    let totalIVA = 0;
    
    // Sumar todas las partidas
    document.querySelectorAll('.partida-row').forEach(function(partida) {
        const precioTotal = parseFloat(partida.querySelector('.partida-precio-total').value) || 0;
        const montoIVA = parseFloat(partida.querySelector('.partida-monto-iva').value) || 0;
        
        subtotal += precioTotal;
        totalIVA += montoIVA;
    });
    
    const total = subtotal + totalIVA;
    
    console.log('Totales recalculados:', { subtotal, totalIVA, total });
    
    // Actualizar displays
    document.getElementById('displaySubtotal').textContent = '$' + formatearNumero(subtotal);
    document.getElementById('displayIVA').textContent = '$' + formatearNumero(totalIVA);
    document.getElementById('displayTotal').textContent = '$' + formatearNumero(total);
    
    // Actualizar campos ocultos
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('iva').value = totalIVA.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);
}

/**
 * Renumerar partidas después de eliminar
 */
function renumerarPartidas() {
    let numero = 1;
    document.querySelectorAll('.partida-row').forEach(function(partida) {
        partida.querySelector('.partida-numero').textContent = numero;
        partida.setAttribute('data-partida-index', numero);
        numero++;
    });
    
    // Actualizar contador
    contadorPartidas = numero - 1;
    console.log('Partidas renumeradas. Total:', contadorPartidas);
}

/**
 * Recopilar datos de todas las partidas
 */
function recopilarPartidas() {
    const partidas = [];
    
    document.querySelectorAll('.partida-row').forEach(function(partida) {
        const codigo = partida.querySelector('.partida-codigo').value.trim();
        const descripcion = partida.querySelector('.partida-descripcion').value.trim();
        const cantidad = parseFloat(partida.querySelector('.partida-cantidad').value) || 0;
        const precioUnitario = parseFloat(partida.querySelector('.partida-precio-unitario').value) || 0;
        const precioTotal = parseFloat(partida.querySelector('.partida-precio-total').value) || 0;
        const diasEntrega = parseInt(partida.querySelector('.partida-dias-entrega').value);
        const unidad = partida.querySelector('.partida-unidad').value;
        const porcentajeIVA = parseFloat(partida.querySelector('.partida-porcentaje-iva').value) || 0;
        const montoIVA = parseFloat(partida.querySelector('.partida-monto-iva').value) || 0;
        
        // Validar que tenga datos mínimos
        if (codigo && descripcion && cantidad > 0) {
            partidas.push({
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
                total_partida: precioTotal + montoIVA
            });
        }
    });
    
    console.log('Partidas recopiladas:', partidas);
    return partidas;
}

/**
 * Validar formulario
 */
function validarFormulario() {
    const clientesid = document.getElementById('clientesid').value;
    const fecha = document.getElementById('fecha').value;
    
    if (!clientesid) {
        Swal.fire('Error', 'Debe seleccionar un cliente', 'error');
        return false;
    }
    
    if (!fecha) {
        Swal.fire('Error', 'Debe especificar la fecha', 'error');
        return false;
    }
    
    return true;
}

/**
 * Formatear número con comas
 */
function formatearNumero(numero) {
    return numero.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}