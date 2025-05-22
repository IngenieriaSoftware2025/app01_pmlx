console.log('🚀 JavaScript EXTERNO cargado');

document.addEventListener('DOMContentLoaded', () => {
    console.log('📄 DOM listo - JavaScript externo');
    
    // Buscar productos al cargar la página
    buscarProductos();
});


async function buscarProductosComprados() {
    console.log('🔍 Buscando productos comprados...');
    try {
        // Usar el endpoint que ya funciona con parámetro
        const respuesta = await fetch('/app01_pmlx/productos/buscarAPI?tipo=comprados');
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

async function buscarProductos() {
    console.log('🔍 Buscando productos...');
    try {
        const respuesta = await fetch('/app01_pmlx/productos/buscarAPI');
        const datos = await respuesta.json();
        console.log('📊 Productos encontrados:', datos);
        
        if (datos.codigo === 1) {
            mostrarProductos(datos.data);
        } else {
            console.log('❌ Error en la respuesta:', datos.mensaje);
        }
    } catch (error) {
        console.log('💥 Error al buscar productos:', error);
    }
}

function mostrarProductos(productos) {
    console.log('📋 Mostrando productos:', productos);
    
    // Separar productos por categoría
    const categorias = { 
        'Alimentos': [], 
        'Higiene': [], 
        'Hogar': [] 
    };
    
    // Agrupar productos por categoría
    productos.forEach(producto => {
        if (categorias[producto.producto_categoria]) {
            categorias[producto.producto_categoria].push(producto);
        }
    });
    
    // Mostrar productos en cada tabla
    Object.keys(categorias).forEach(categoria => {
        const tbody = document.getElementById(`tbody-${categoria.toLowerCase()}`);
        const productosCategoria = categorias[categoria];
        
        console.log(`📋 Categoría ${categoria}:`, productosCategoria);
        
        if (!tbody) {
            console.log(`❌ No se encontró tbody para: tbody-${categoria.toLowerCase()}`);
            return;
        }
        
        if (productosCategoria.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No hay productos en esta categoría</td></tr>';
        } else {
            tbody.innerHTML = productosCategoria.map(producto => `
                <tr>
                    <td><strong>${producto.producto_nombre}</strong></td>
                    <td><span class="badge bg-primary">${producto.producto_cantidad}</span></td>
                    <td><span class="badge bg-${obtenerColorPrioridad(producto.producto_prioridad)}">${producto.producto_prioridad}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-success" onclick="marcarComprado(${producto.producto_id})" title="Marcar como comprado">
                            <i class="bi bi-check-circle"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick="editarProducto(${producto.producto_id})" title="Editar producto">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="eliminarProducto(${producto.producto_id})" title="Eliminar producto">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }
    });
}

function obtenerColorPrioridad(prioridad) {
    switch(prioridad) {
        case 'Alta': return 'danger';
        case 'Media': return 'warning';
        case 'Baja': return 'success';
        default: return 'secondary';
    }
}

async function marcarComprado(id) {
    console.log('✅ Marcando producto como comprado:', id);
    try {
        const respuesta = await fetch(`/app01_pmlx/productos/marcarComprado?id=${id}`, {
            method: 'POST'
        });
        const datos = await respuesta.json();
        
        if (datos.codigo === 1) {
            console.log('✅ Producto marcado como comprado exitosamente');
            alert('✅ Producto marcado como comprado');
            buscarProductos(); // Recargar la lista


             if (datos.productos_comprados) {
                mostrarProductosComprados(datos.productos_comprados);
            }
        } else {
            console.log('❌ Error al marcar como comprado:', datos.mensaje);
            alert('❌ Error: ' + datos.mensaje);
        }
    } catch (error) {
        console.log('💥 Error al marcar como comprado:', error);
        alert('💥 Error de conexión: ' + error.message);
    }
}




async function editarProducto(id) {
    // Función para editar productos (puedes implementarla después)
    console.log('✏️ Editar producto:', id);
    alert('Función de edición pendiente de implementar');
}

async function eliminarProducto(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
        console.log('🗑️ Eliminando producto:', id);
        try {
            const respuesta = await fetch(`/app01_pmlx/productos/eliminar?id=${id}`, {
                method: 'DELETE'
            });
            const datos = await respuesta.json();
            
            if (datos.codigo === 1) {
                console.log('✅ Producto eliminado exitosamente');
                alert('✅ Producto eliminado');
                buscarProductos(); // Recargar la lista
            } else {
                console.log('❌ Error al eliminar:', datos.mensaje);
                alert('❌ Error: ' + datos.mensaje);
            }
        } catch (error) {
            console.log('💥 Error al eliminar:', error);
            alert('💥 Error de conexión: ' + error.message);
        }
    }
}