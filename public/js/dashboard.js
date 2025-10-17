/**
 * JavaScript del Dashboard Principal
 * 
 * Maneja animaciones y funcionalidades del dashboard
 */

// Ejecutar cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    
    console.log('Dashboard cargado correctamente');
    
    /**
     * Animación de contador para las estadísticas
     */
    animarContadores();
    
    /**
     * Resaltar link activo en el navbar
     */
    resaltarLinkActivo();
    
    /**
     * Agregar efecto hover a las tarjetas
     */
    efectosHoverTarjetas();
    
});

/**
 * Anima los números de las estadísticas
 * Efecto de contador incremental
 */
function animarContadores() {
    const contadores = document.querySelectorAll('.stat-number');
    
    contadores.forEach(contador => {
        const valorFinal = parseInt(contador.textContent.replace(/,/g, ''));
        const duracion = 1500; // milisegundos
        const incremento = valorFinal / (duracion / 16); // 60 FPS
        let valorActual = 0;
        
        const timer = setInterval(() => {
            valorActual += incremento;
            
            if (valorActual >= valorFinal) {
                contador.textContent = formatearNumero(valorFinal);
                clearInterval(timer);
            } else {
                contador.textContent = formatearNumero(Math.floor(valorActual));
            }
        }, 16);
    });
}

/**
 * Formatea un número con separadores de miles
 * 
 * @param {number} numero - Número a formatear
 * @return {string} - Número formateado
 */
function formatearNumero(numero) {
    return numero.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * Resalta el link activo en el navbar
 */
function resaltarLinkActivo() {
    const urlActual = window.location.pathname;
    const links = document.querySelectorAll('.nav-link');
    
    links.forEach(link => {
        const href = link.getAttribute('href');
        
        // Remover clase active de todos
        link.classList.remove('active');
        
        // Agregar active al link actual
        if (urlActual.includes(href) || (href === window.location.origin + '/' && urlActual === '/')) {
            link.classList.add('active');
        }
    });
}

/**
 * Agrega efectos de hover a las tarjetas
 */
function efectosHoverTarjetas() {
    const tarjetas = document.querySelectorAll('.stat-card, .quick-link');
    
    tarjetas.forEach(tarjeta => {
        tarjeta.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        tarjeta.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

/**
 * Función para actualizar las estadísticas sin recargar (opcional)
 * Puede ser llamada cada cierto tiempo
 */
function actualizarEstadisticas() {
    // Implementar llamada AJAX para actualizar estadísticas en tiempo real
    console.log('Actualizando estadísticas...');
    
    // Ejemplo de uso:
    // setInterval(actualizarEstadisticas, 60000); // Actualizar cada minuto
}

/**
 * Efecto de partículas en el fondo (opcional - decorativo)
 */
function efectoParticulas() {
    // Implementación opcional de efecto visual
    console.log('Efecto de partículas activado');
}

/**
 * Obtener saludo según la hora del día
 */
function obtenerSaludo() {
    const hora = new Date().getHours();
    
    if (hora < 12) {
        return 'Buenos días';
    } else if (hora < 18) {
        return 'Buenas tardes';
    } else {
        return 'Buenas noches';
    }
}

