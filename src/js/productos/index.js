console.log('🚀 JavaScript cargado');

// Esperar a que la página esté lista
document.addEventListener('DOMContentLoaded', () => {
    console.log('📄 Página lista');
    
    const FormProductos = document.getElementById('FormProductos');
    const BtnGuardar = document.getElementById('BtnGuardar');
    
    console.log('📝 Formulario:', FormProductos ? 'ENCONTRADO' : 'NO ENCONTRADO');
    console.log('🔘 Botón:', BtnGuardar ? 'ENCONTRADO' : 'NO ENCONTRADO');
    
    if (FormProductos) {
        FormProductos.addEventListener('submit', async (event) => {
            event.preventDefault();
            console.log('⚡ BOTÓN PRESIONADO - INICIANDO PROCESO');
            
            // Obtener valores directamente
            const nombre = document.getElementById('producto_nombre')?.value || '';
            const cantidad = document.getElementById('producto_cantidad')?.value || '';
            const categoria = document.getElementById('producto_categoria')?.value || '';
            const prioridad = document.getElementById('producto_prioridad')?.value || '';
            
            console.log('📋 VALORES:');
            console.log('- Nombre:', `"${nombre}"`);
            console.log('- Cantidad:', `"${cantidad}"`);
            console.log('- Categoría:', `"${categoria}"`);
            console.log('- Prioridad:', `"${prioridad}"`);
            
            // Validación súper simple
            if (nombre === '' || cantidad === '' || categoria === '' || prioridad === '') {
                console.log('❌ CAMPOS VACÍOS DETECTADOS');
                alert('Por favor completa todos los campos');
                return;
            }
            
            console.log('✅ TODOS LOS CAMPOS COMPLETOS');
            
            // Intentar enviar
            try {
                const formData = new FormData();
                formData.append('producto_nombre', nombre);
                formData.append('producto_cantidad', cantidad);
                formData.append('producto_categoria', categoria);
                formData.append('producto_prioridad', prioridad);
                
                console.log('🌐 ENVIANDO A: /app01_pmlx/productos/guardarAPI');
                
                const respuesta = await fetch('/app01_pmlx/productos/guardarAPI', {
                    method: 'POST',
                    body: formData
                });
                
                console.log('📨 RESPUESTA RECIBIDA:', respuesta.status);
                
                const resultado = await respuesta.json();
                console.log('📦 DATOS:', resultado);
                
                if (resultado.codigo === 1) {
                    alert('¡Producto agregado exitosamente!');
                    FormProductos.reset();
                    document.getElementById('producto_prioridad').value = 'Media';
                } else {
                    alert('Error: ' + resultado.mensaje);
                }
                
            } catch (error) {
                console.log('💥 ERROR:', error);
                alert('Error de conexión: ' + error.message);
            }
        });
    }
});

// Función para probar manualmente
window.probarFormulario = () => {
    console.log('🧪 PRUEBA MANUAL');
    const nombre = document.getElementById('producto_nombre')?.value;
    const cantidad = document.getElementById('producto_cantidad')?.value;
    const categoria = document.getElementById('producto_categoria')?.value;
    const prioridad = document.getElementById('producto_prioridad')?.value;
    
    console.log('Valores actuales:');
    console.log('- Nombre:', nombre);
    console.log('- Cantidad:', cantidad);
    console.log('- Categoría:', categoria);
    console.log('- Prioridad:', prioridad);
}