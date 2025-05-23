<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #28a745;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h5 class="text-center mb-2">Bienvenida María</h5>
                    <h4 class="text-center mb-2 text-success">¡Ingresa tú listado de Compras!</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">
                    <form id="FormProductos">
                        <input type="hidden" id="producto_id" name="producto_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="producto_nombre" class="form-label">NOMBRE DEL PRODUCTO</label>
                                <input type="text" class="form-control" id="producto_nombre" name="producto_nombre" placeholder="Ej: Papel higiénico" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="producto_cantidad" class="form-label">CANTIDAD</label>
                                <input type="number" class="form-control" id="producto_cantidad" name="producto_cantidad" placeholder="Ej: 3" min="1" value="1" required>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="producto_categoria" class="form-label">CATEGORÍA</label>
                                <select name="producto_categoria" class="form-select" id="producto_categoria" required>
                                    <option value="">-- SELECCIONE LA CATEGORÍA --</option>
                                    <option value="Alimentos">ALIMENTOS</option>
                                    <option value="Higiene">HIGIENE</option>
                                    <option value="Hogar">HOGAR</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="producto_prioridad" class="form-label">PRIORIDAD</label>
                                <select name="producto_prioridad" class="form-select" id="producto_prioridad" required>
                                    <option value="">-- SELECCIONE LA PRIORIDAD --</option>
                                    <option value="Alta">ALTA</option>
                                    <option value="Media" selected>MEDIA</option>
                                    <option value="Baja">BAJA</option>
                                </select>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5">
                            <div class="col-auto">
                                <button class="btn btn-success" type="submit" id="BtnGuardar">
                                    <i class="bi bi-plus-circle me-1"></i>Agregar Producto
                                </button>
                            </div>

                         

                            <div class="col-auto">
                                <button class="btn btn-secondary" type="reset" id="BtnLimpiar">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECCIÓN DE PRODUCTOS POR COMPRAR -->
<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #17a2b8;">
            <div class="card-body p-3">
                <h3 class="text-center text-info">🛒 PRODUCTOS POR COMPRAR</h3>

                <ul class="nav nav-tabs" id="categoriaTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="alimentos-tab" data-bs-toggle="tab" data-bs-target="#alimentos" type="button" role="tab">
                            🍎 ALIMENTOS
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="higiene-tab" data-bs-toggle="tab" data-bs-target="#higiene" type="button" role="tab">
                            🧼 HIGIENE
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="hogar-tab" data-bs-toggle="tab" data-bs-target="#hogar" type="button" role="tab">
                            🏠 HOGAR
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="categoriaTabsContent">
                    <div class="tab-pane fade show active" id="alimentos" role="tabpanel">
                        <div class="table-responsive p-2">
                            <table class="table table-striped table-hover table-bordered w-100 table-sm">
                                <thead class="table-success">
                                    <tr><th>Producto</th><th>Cantidad</th><th>Prioridad</th><th>Acciones</th></tr>
                                </thead>
                                <tbody id="tbody-alimentos"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="higiene" role="tabpanel">
                        <div class="table-responsive p-2">
                            <table class="table table-striped table-hover table-bordered w-100 table-sm">
                                <thead class="table-info">
                                    <tr><th>Producto</th><th>Cantidad</th><th>Prioridad</th><th>Acciones</th></tr>
                                </thead>
                                <tbody id="tbody-higiene"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="hogar" role="tabpanel">
                        <div class="table-responsive p-2">
                            <table class="table table-striped table-hover table-bordered w-100 table-sm">
                                <thead class="table-warning">
                                    <tr><th>Producto</th><th>Cantidad</th><th>Prioridad</th><th>Acciones</th></tr>
                                </thead>
                                <tbody id="tbody-hogar"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PRODUCTOS COMPRADOS -->
<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #6c757d;">
            <div class="card-body p-3">
                <h3 class="text-center text-secondary">✅ PRODUCTOS COMPRADOS</h3>
                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm">
                        <thead class="table-secondary">
                            <tr><th>Producto</th><th>Cantidad</th><th>Categoría</th><th>Fecha</th><th>Acciones</th></tr>
                        </thead>
                        <tbody id="tbody-comprados"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?= asset('build/js/productos/index.js') ?>"></script>
















<script>
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
            notificacion.style.transition = 'opacity 0.3s';
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
</script>


