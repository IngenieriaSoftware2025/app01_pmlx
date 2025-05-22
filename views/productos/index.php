<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card shadow-lg border-primary">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h5 class="text-center mb-2">¡Bienvenido a la Aplicación!</h5>
                    <h4 class="text-center mb-2 text-primary">Es para ayudar a María quien es una madre de familia que cada semana organiza las compras del hogar</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg rounded">
                    <form id="FormProductos">
                        <input type="hidden" id="productos_id" name="productos_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="productos_nombre" class="form-label">NOMBRE DEL PRODUCTO</label>
                                <input type="text" class="form-control" id="productos_nombre" name="productos_nombre" placeholder="Ingrese el nombre del producto" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="productos_cantidad" class="form-label">CANTIDAD</label>
                                <input type="number" class="form-control" id="productos_cantidad" name="productos_cantidad" placeholder="Ingrese la cantidad" min="0" required>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="categoria_id" class="form-label">CATEGORÍA DEL PRODUCTO</label>
                                <select name="categoria_id" class="form-select" id="categoria_id" required>
                                    <option value="" class="text-center"> -- SELECCIONE LA CATEGORÍA -- </option>
                                    <option value="1">Alimentos</option>
                                    <option value="2">Higiene</option>
                                    <option value="3">Hogar</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="prioridad_id" class="form-label">PRIORIDAD DEL PRODUCTO</label>
                                <select name="prioridad_id" class="form-select" id="prioridad_id" required>
                                    <option value="" class="text-center"> -- SELECCIONE LA PRIORIDAD -- </option>
                                    <option value="1">Alta</option>
                                    <option value="2">Media</option>
                                    <option value="3">Baja</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="productos_fecha_compra" class="form-label">FECHA DE COMPRA</label>
                                <input type="date" class="form-control" id="productos_fecha_compra" name="productos_fecha_compra">
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5">
                            <div class="col-auto">
                                <button class="btn btn-success" type="submit" id="BtnGuardar">
                                    Guardar
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-warning d-none" type="button" id="BtnModificar">
                                    Modificar
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-secondary" type="reset" id="BtnLimpiar">
                                    Limpiar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card shadow-lg border-primary">
            <div class="card-body p-3">
                <h3 class="text-center">PRODUCTOS REGISTRADOS EN LA BASE DE DATOS</h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableProductos">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Categoría</th>
                                <th class="text-center">Prioridad</th>
                                <th class="text-center">Comprado</th>
                                <th class="text-center">Fecha Compra</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los datos se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ← AGREGAR EL SCRIPT COMPILADO -->
<script src="<?= asset('build/js/productos/index.js') ?>"></script>