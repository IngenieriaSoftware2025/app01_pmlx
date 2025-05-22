
import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario, Toast } from '../funciones';

document.addEventListener('DOMContentLoaded', function() {
    const FormProductos = document.getElementById('FormProductos');
    const BtnGuardar = document.getElementById('BtnGuardar');
    const BtnModificar = document.getElementById('BtnModificar');
    const BtnLimpiar = document.getElementById('BtnLimpiar');
    
    // Verificar que los elementos existen
    if (!FormProductos) {
        console.error('No se encontró el formulario FormProductos');
        return;
    }

    // Cargar datos iniciales
    buscarProductos();

    // Manejar envío del formulario
    FormProductos.addEventListener('submit', GuardarProductos);
    
    // Manejar botón limpiar
    if (BtnLimpiar) {
        BtnLimpiar.addEventListener('click', function() {
            FormProductos.reset();
            document.getElementById('productos_id').value = '';
            BtnGuardar.classList.remove('d-none');
            BtnModificar.classList.add('d-none');
        });
    }

    // Manejar modificar
    if (BtnModificar) {
        BtnModificar.addEventListener('click', ModificarProducto);
    }
});

const GuardarProductos = async (e) => {
    e.preventDefault();
    const BtnGuardar = document.getElementById('BtnGuardar');
    const FormProductos = document.getElementById('FormProductos');
    
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormProductos, ['productos_id'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormProductos);
    const url = '/app01_pmlx/productos/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (datos.codigo === 1) {
            Toast.fire({
                icon: 'success',
                title: datos.mensaje
            });
            FormProductos.reset();
            buscarProductos(); // Recargar tabla
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: datos.mensaje
            });
        }
    } catch (error) {
        console.log(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión'
        });
    } finally {
        BtnGuardar.disabled = false;
    }
}


FormProductos.addEventListener('submit', GuardarProductos);


