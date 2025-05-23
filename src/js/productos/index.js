
console.log('🚀 JavaScript INLINE cargado');

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
                alert('❌ Por favor completa todos los campos');
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
                    alert('✅ ¡Producto agregado exitosamente!');
                    FormProductos.reset();
                    document.getElementById('producto_prioridad').value = 'Media';
                    buscarProductos();
                } else {
                    alert('❌ Error: ' + resultado.mensaje);
                }
                
            } catch (error) {
                console.log('💥 Error:', error);
                alert('💥 Error de conexión: ' + error.message);
            }
            
            BtnGuardar.disabled = false;
            BtnGuardar.innerHTML = '<i class="bi bi-plus-circle me-1"></i>Agregar Producto';
        });
    }
    
    if (BtnLimpiar) {
        BtnLimpiar.addEventListener('click', () => {
            FormProductos.reset();
            document.getElementById('producto_prioridad').value = 'Media';
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
            alert('✅ Producto marcado como comprado');
            buscarProductos(); // Actualizar productos pendientes
            buscarProductosComprados(); // ⭐ Actualizar productos comprados
        }
    } catch (error) {
        console.log('Error:', error);
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
            alert('↩️ Producto regresado a la lista de compras');
            buscarProductos(); // Actualizar productos pendientes
            buscarProductosComprados(); // Actualizar productos comprados
        } else {
            console.log('❌ Error al regresar a pendientes:', datos.mensaje);
            alert('❌ Error: ' + datos.mensaje);
        }
    } catch (error) {
        console.log('💥 Error al regresar a pendientes:', error);
        alert('💥 Error de conexión: ' + error.message);
    }
}

// ⭐ NUEVA FUNCIÓN: Eliminar definitivamente
async function eliminarDefinitivo(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este producto DEFINITIVAMENTE?')) {
        console.log('🗑️ Eliminando definitivamente producto:', id);
        try {
            const respuesta = await fetch(`/app01_pmlx/productos/eliminar?id=${id}`);
            const datos = await respuesta.json();
            
            if (datos.codigo === 1) {
                console.log('✅ Producto eliminado definitivamente');
                alert('🗑️ Producto eliminado definitivamente');
                buscarProductosComprados(); // Actualizar productos comprados
            } else {
                console.log('❌ Error al eliminar definitivamente:', datos.mensaje);
                alert('❌ Error: ' + datos.mensaje);
            }
        } catch (error) {
            console.log('💥 Error al eliminar definitivamente:', error);
            alert('💥 Error de conexión: ' + error.message);
        }
    }
}

