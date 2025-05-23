console.log('🚀 JavaScript INLINE cargado');

// ⭐ NOTIFICACIONES SENCILLAS PERO BONITAS
function mostrarNotificacion(mensaje, tipo = 'success') {
    // Crear contenedor si no existe
    let contenedor = document.getElementById('notification-container');
    if (!contenedor) {
        contenedor = document.createElement('div');
        contenedor.id = 'notification-container';
        contenedor.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: 300px;
        `;
        document.body.appendChild(contenedor);
    }
    
    // Crear la notificación
    const notificacion = document.createElement('div');
    const esExito = tipo === 'success';
    const icono = esExito ? '✅' : '❌';
    const color = esExito ? '#28a745' : '#dc3545';
    
    notificacion.innerHTML = `
        <div style="
            background: white;
            border: 2px solid ${color};
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        ">
            <span style="font-size: 16px;">${icono}</span>
            <span>${mensaje}</span>
        </div>
    `;
    
    contenedor.appendChild(notificacion);
    
    // Auto-eliminar después de 3 segundos
    setTimeout(() => {
        if (notificacion.parentNode) {
            notificacion.style.opacity = '0';
            notificacion.style.transition = 'opacity 0.2s';
            setTimeout(() => {
                notificacion.parentNode.removeChild(notificacion);
            }, 300);
        }
    }, 3000);
}

// ⭐ CONFIRMACIÓN SENCILLA
function mostrarConfirmacion(mensaje) {
    return new Promise((resolve) => {
        // Crear overlay simple
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 10000;
            display: flex;
            justify-content: center;
            align-items: center;
        `;
        
        // Crear modal simple
        const modal = document.createElement('div');
        modal.style.cssText = `
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            max-width: 350px;
            width: 90%;
            text-align: center;
            font-family: Arial, sans-serif;
        `;
        
        modal.innerHTML = `
            <div style="margin-bottom: 20px;">
                <span style="font-size: 32px;">🤔</span>
                <p style="margin: 10px 0 0 0; font-size: 16px; color: #333;">${mensaje}</p>
            </div>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button id="btn-si" style="
                    background: #28a745;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                ">Sí</button>
                <button id="btn-no" style="
                    background: #6c757d;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                ">No</button>
            </div>
        `;
        
        document.body.appendChild(overlay);
        overlay.appendChild(modal);
        
        // Manejar respuestas
        function cerrarModal(resultado) {
            document.body.removeChild(overlay);
            resolve(resultado);
        }
        
        modal.querySelector('#btn-si').onclick = () => cerrarModal(true);
        modal.querySelector('#btn-no').onclick = () => cerrarModal(false);
        overlay.onclick = (e) => {
            if (e.target === overlay) cerrarModal(false);
        };
    });
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('📄 DOM listo');
    
    const FormProductos = document.getElementById('FormProductos');
    const BtnGuardar = document.getElementById('BtnGuardar');
    const BtnLimpiar = document.getElementById('BtnLimpiar');
    
    console.log('Elementos encontrados:');
    console.log('- Formulario:', FormProductos ? 'SÍ' : 'NO');
    console.log('- Botón Guardar:', BtnGuardar ? 'SÍ' : 'NO');
    
    if (FormProductos) {
        FormProductos.addEventListener('submit', async (event) => {
            event.preventDefault();
            console.log('🔥 FORMULARIO ENVIADO');
            
            BtnGuardar.disabled = true;
            BtnGuardar.innerHTML = '⏳ Guardando...';
            
            const nombre = document.getElementById('producto_nombre').value.trim();
            const cantidad = document.getElementById('producto_cantidad').value;
            const categoria = document.getElementById('producto_categoria').value;
            const prioridad = document.getElementById('producto_prioridad').value;
            
            console.log('📋 Datos del formulario:');
            console.log('- Nombre:', `"${nombre}"`);
            console.log('- Cantidad:', cantidad);
            console.log('- Categoría:', categoria);
            console.log('- Prioridad:', prioridad);
            
            if (!nombre || !cantidad || !categoria || !prioridad) {
                mostrarNotificacion('Por favor completa todos los campos', 'error');
                BtnGuardar.disabled = false;
                BtnGuardar.innerHTML = '<i class="bi bi-plus-circle me-1"></i>Agregar Producto';
                return;
            }
            
            try {
                const formData = new FormData();
                formData.append('producto_nombre', nombre);
                formData.append('producto_cantidad', cantidad);
                formData.append('producto_categoria', categoria);
                formData.append('producto_prioridad', prioridad);
                
                console.log('🌐 Enviando a: /app01_pmlx/productos/guardarAPI');
                
                const respuesta = await fetch('/app01_pmlx/productos/guardarAPI', {
                    method: 'POST',
                    body: formData
                });
                
                console.log('📨 Respuesta status:', respuesta.status);
                
                const resultado = await respuesta.json();
                console.log('📦 Resultado:', resultado);
                
                if (resultado.codigo === 1) {
                    mostrarNotificacion('¡Producto agregado exitosamente!', 'success');
                    FormProductos.reset();
                    document.getElementById('producto_prioridad').value = 'Media';
                    buscarProductos();
                } else {
                    mostrarNotificacion('Error: ' + resultado.mensaje, 'error');
                }
                
            } catch (error) {
                console.log('💥 Error:', error);
                mostrarNotificacion('Error de conexión: ' + error.message, 'error');
            }
            
            BtnGuardar.disabled = false;
            BtnGuardar.innerHTML = '<i class="bi bi-plus-circle me-1"></i>Agregar Producto';
        });
    }
    
    if (BtnLimpiar) {
        BtnLimpiar.addEventListener('click', () => {
            FormProductos.reset();
            document.getElementById('producto_prioridad').value = 'Media';
            mostrarNotificacion('Formulario limpiado', 'success');
        });
    }
    
    // ⭐ CARGAR PRODUCTOS PENDIENTES Y COMPRADOS AL INICIO
    buscarProductos();
    buscarProductosComprados();
});

async function buscarProductos() {
    console.log('🔍 Buscando productos...');
    try {
        const respuesta = await fetch('/app01_pmlx/productos/buscarAPI');
        const datos = await respuesta.json();
        console.log('📊 Productos encontrados:', datos);
        
        if (datos.codigo === 1) {
            mostrarProductos(datos.data);
        }
    } catch (error) {
        console.log('Error al buscar productos:', error);
        mostrarNotificacion('Error al cargar productos', 'error');
    }
}

// ⭐ NUEVA FUNCIÓN: Buscar productos comprados
async function buscarProductosComprados() {
    console.log('🔍 Buscando productos comprados...');
    try {
        const respuesta = await fetch('/app01_pmlx/productos/buscarCompradosAPI');
        const datos = await respuesta.json();
        console.log('📊 Productos comprados encontrados:', datos);
        
        if (datos.codigo === 1) {
            mostrarProductosComprados(datos.data);
        } else {
            console.log('❌ Error en la respuesta:', datos.mensaje);
        }
    } catch (error) {
        console.log('💥 Error al buscar productos comprados:', error);
    }
}

// ⭐ NUEVA FUNCIÓN: Mostrar productos comprados
function mostrarProductosComprados(productos) {
    console.log('📋 Mostrando productos comprados:', productos);
    
    const tbody = document.getElementById('tbody-comprados');
    
    if (!tbody) {
        console.log('❌ No se encontró tbody-comprados');
        return;
    }
    
    if (productos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No hay productos comprados</td></tr>';
    } else {
        tbody.innerHTML = productos.map(producto => `
            <tr>
                <td><strong>${producto.producto_nombre}</strong></td>
                <td><span class="badge bg-primary">${producto.producto_cantidad}</span></td>
                <td><span class="badge bg-info">${producto.producto_categoria}</span></td>
                <td><span class="badge bg-secondary">${new Date().toLocaleDateString()}</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-warning" onclick="regresarPendiente(${producto.producto_id})" title="Regresar a pendientes">
                        <i class="bi bi-arrow-left-circle"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarDefinitivo(${producto.producto_id})" title="Eliminar definitivamente">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }
}

function mostrarProductos(productos) {
    console.log('📋 Mostrando productos:', productos);
    
    const categorias = { 'Alimentos': [], 'Higiene': [], 'Hogar': [] };
    
    productos.forEach(producto => {
        if (categorias[producto.producto_categoria]) {
            categorias[producto.producto_categoria].push(producto);
        }
    });
    
    Object.keys(categorias).forEach(categoria => {
        const tbody = document.getElementById(`tbody-${categoria.toLowerCase()}`);
        const productosCategoria = categorias[categoria];
        
        if (productosCategoria.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No hay productos</td></tr>';
        } else {
            tbody.innerHTML = productosCategoria.map(producto => `
                <tr>
                    <td><strong>${producto.producto_nombre}</strong></td>
                    <td><span class="badge bg-primary">${producto.producto_cantidad}</span></td>
                    <td><span class="badge bg-${producto.producto_prioridad === 'Alta' ? 'danger' : producto.producto_prioridad === 'Media' ? 'warning' : 'success'}">${producto.producto_prioridad}</span></td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="marcarComprado(${producto.producto_id})">
                            <i class="bi bi-check"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }
    });
}

// ⭐ FUNCIÓN MODIFICADA: Marcar como comprado y actualizar ambas listas
async function marcarComprado(id) {
    try {
        const respuesta = await fetch(`/app01_pmlx/productos/marcarComprado?id=${id}`);
        const datos = await respuesta.json();
        if (datos.codigo === 1) {
            mostrarNotificacion('Producto marcado como comprado', 'success');
            buscarProductos(); // Actualizar productos pendientes
            buscarProductosComprados(); // ⭐ Actualizar productos comprados
        } else {
            mostrarNotificacion('Error al marcar como comprado', 'error');
        }
    } catch (error) {
        console.log('Error:', error);
        mostrarNotificacion('Error de conexión', 'error');
    }
}

// ⭐ NUEVA FUNCIÓN: Regresar producto a pendientes
async function regresarPendiente(id) {
    console.log('↩️ Regresando producto a pendientes:', id);
    try {
        const respuesta = await fetch(`/app01_pmlx/productos/desmarcarComprado?id=${id}`);
        const datos = await respuesta.json();
        
        if (datos.codigo === 1) {
            console.log('✅ Producto regresado a pendientes exitosamente');
            mostrarNotificacion('Producto regresado a la lista de compras', 'success');
            buscarProductos(); // Actualizar productos pendientes
            buscarProductosComprados(); // Actualizar productos comprados
        } else {
            console.log('❌ Error al regresar a pendientes:', datos.mensaje);
            mostrarNotificacion('Error: ' + datos.mensaje, 'error');
        }
    } catch (error) {
        console.log('💥 Error al regresar a pendientes:', error);
        mostrarNotificacion('Error de conexión: ' + error.message, 'error');
    }
}

// ⭐ NUEVA FUNCIÓN: Eliminar definitivamente
async function eliminarDefinitivo(id) {
    const confirmado = await mostrarConfirmacion('¿Estás seguro de eliminar este producto definitivamente?');
    
    if (confirmado) {
        console.log('🗑️ Eliminando definitivamente producto:', id);
        try {
            const respuesta = await fetch(`/app01_pmlx/productos/eliminar?id=${id}`);
            const datos = await respuesta.json();
            
            if (datos.codigo === 1) {
                console.log('✅ Producto eliminado definitivamente');
                mostrarNotificacion('Producto eliminado definitivamente', 'success');
                buscarProductosComprados(); // Actualizar productos comprados
            } else {
                console.log('❌ Error al eliminar definitivamente:', datos.mensaje);
                mostrarNotificacion('Error: ' + datos.mensaje, 'error');
            }
        } catch (error) {
            console.log('💥 Error al eliminar definitivamente:', error);
            mostrarNotificacion('Error de conexión: ' + error.message, 'error');
        }
    }
}

