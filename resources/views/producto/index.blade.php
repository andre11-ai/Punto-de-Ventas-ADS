@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Productos</h1>
@stop

@section('css')
    <link href="DataTables/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            {{ __('Producto') }}
                        </span>

<div class="float-right d-flex gap-2">
  <button class="btn btn-warning btn-sm" id="btnPromociones" data-bs-toggle="modal" data-bs-target="#promocionModal">
    Promociones
  </button>
  <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm">
    {{ __('Create New') }}
  </a>
</div>

                    </div>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert fade_success .fade">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                  <div class="table-responsive">
        <table class="table table-striped table-hover display responsive nowrap" width="100%" id="tblProducts">
            <thead class="thead">
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>Categoría</th>
                    <th>Proveedor</th>
                    <th>Imagen</th>
                    <th>Código de Barras</th>
                    <th>Promoción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>

                </div>
            </div>
        </div>
    </div>

<!-- Modal Promociones -->
<div class="modal fade" id="promocionModal" tabindex="-1" aria-labelledby="promocionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="promocionModalLabel">Gestión de Promociones</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario para nueva promoción -->
        <form method="POST" action="{{ route('promociones.guardar') }}" id="formPromocion">
          @csrf
        <div class="row mb-4">
    <div class="col-md-4">
        <label for="id_categoria">Categoría</label>
        <select class="form-select" name="id_categoria" id="selectCategoria">
            <option value="" selected>-- Ninguna --</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="id_proveedor">Proveedor</label>
        <select class="form-select" name="id_proveedor" id="selectProveedor">
            <option value="" selected>-- Ninguno --</option>
            @foreach($proveedores as $proveedor)
                <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="tipo_promocion">Tipo de Promoción </label>
        <select name="tipo_promocion" class="form-select" required>
            <option value="ninguna" selected disabled>-- Seleccione --</option>
            <option value="2x1">2x1</option>
            <option value="3x2">3x2</option>
            <option value="50%">50% de descuento</option>
            <option value="Precio especial">Precio especial</option>
            <option value="Segunda unidad al 30%">Segunda unidad al 30%</option>
        </select>
        <div class="invalid-feedback">Por favor selecciona un tipo de promoción</div>
    </div>
</div>
          <div class="text-end mb-3">
            <button type="submit" class="btn btn-success">Aplicar Promoción</button>
          </div>
        </form>

        <!-- Listado de productos con promoción -->
<div class="table-responsive mt-4">
    <table class="table table-striped" id="tblPromociones">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Proveedor</th>
                <th>Promoción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productosConPromocion as $producto)
                <tr>
                    <td>{{ $producto->producto }}</td>
                    <td>{{ $producto->categoria->nombre ?? '-' }}</td>
                    <td>{{ $producto->proveedor->nombre ?? '-' }}</td>
                    <td>
                        <span class="badge bg-warning text-dark">
                            {{ $producto->promocion->tipo ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary btn-editar-promocion"
                                data-id="{{ $producto->id }}"
                                data-tipo="{{ $producto->promocion->tipo ?? '' }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-eliminar-promocion"
                                data-id="{{ $producto->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="editarPromocionModal" tabindex="-1" aria-hidden="true">
  <!-- Contenido se cargará dinámicamente -->
</div>


@stop
@section('js')
    <!-- Orden CORRECTO de carga de librerías -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="DataTables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <script>
        $(document).ready(function() {
            // Configuración de DataTable principal
            const table = new DataTable('#tblProducts', {
                responsive: true,
                fixedHeader: true,
                ajax: {
                    url: '{{ route('products.list') }}',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id' },
                    { data: 'codigo' },
                    { data: 'producto' },
                    { data: 'precio_compra' },
                    { data: 'precio_venta' },
                    {
                        data: 'categoria',
                        render: function(data) {
                            return data ? data.nombre : '-';
                        }
                    },
                    {
                        data: 'proveedor',
                        render: function(data) {
                            return data ? data.nombre : '-';
                        }
                    },
                    {
                        data: 'foto',
                        render: function(data) {
                            return data ? `<img src="/storage/${data}" alt="Foto" style="max-width: 60px;">` : 'Sin imagen';
                        }
                    },
                    {
                        data: 'codigo_barras',
                        render: function(data, type, row) {
                            const barcodeId = `barcode-${row.id}`;
                            setTimeout(() => {
                                JsBarcode(`#${barcodeId}`, data, {
                                    format: "EAN13",
                                    width: 1.4,
                                    height: 40,
                                    displayValue: false
                                });
                            }, 100);
                            return `<svg id="${barcodeId}"></svg><small>${data}</small>`;
                        }
                    },
                    {
                        data: 'promocion',
                        render: function(data) {
                            return data && data.tipo
                                ? `<span class="badge bg-warning text-dark">${data.tipo}</span>`
                                : '<span class="text-muted">Sin promoción</span>';
                        }
                    },
                    {
                        data: null,
                        render: function(row) {
                            return `
                                <a href="/productos/${row.id}/edit" class="btn btn-sm btn-primary">Editar</a>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}">Eliminar</button>
                            `;
                        }
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                order: [[0, 'desc']]
            });

            // Botón para abrir modal de promociones
            $('#btnPromociones').on('click', function() {
                $('#promocionModal').modal('show');
            });

            // Manejar clic en botón editar promoción (VERSIÓN CORREGIDA)
            $(document).on('click', '.btn-editar-promocion', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Evita que otros manejadores se ejecuten

                let productoId = $(this).data('id');
                let tipoPromocion = $(this).data('tipo');

                console.log('Editando promoción para producto ID:', productoId);

                // Usar el método load() de jQuery para simplificar
                $('#editarPromocionModal').load(`/productos/${productoId}/edit-promocion`, function() {
                    $(this).modal('show');

                    // Establecer el valor seleccionado si existe
                    if(tipoPromocion) {
                        $(this).find('select[name="tipo_promocion"]').val(tipoPromocion);
                    }
                }).fail(function() {
                    Swal.fire('Error', 'No se pudo cargar el formulario de edición', 'error');
                });
            });

            // Manejar envío del formulario de edición
              $(document).on('submit', '#formPromocion', function(e) {
    const categoria = $('#selectCategoria').val();
    const proveedor = $('#selectProveedor').val();
    const tipoPromocion = $('select[name="tipo_promocion"]').val();

    // Validación: Al menos uno de los dos (categoría o proveedor) debe estar seleccionado
    if (!categoria && !proveedor) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Selección requerida',
            text: 'Debes seleccionar al menos una categoría o un proveedor',
        });
        return false;
    }

    // Validación: El tipo de promoción no puede ser "ninguna"
    if (tipoPromocion === 'ninguna') {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Tipo de promoción requerido',
            text: 'Debes seleccionar un tipo de promoción válido',
        });
        return false;
    }

    return true;
});
            // Manejar clic en botón eliminar promoción
            $(document).on('click', '.btn-eliminar-promocion', function() {
                let productoId = $(this).data('id');

                Swal.fire({
                    title: '¿Eliminar promoción?',
                    text: "¿Estás seguro de quitar la promoción de este producto?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/productos/${productoId}/remove-promocion`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire(
                                    '¡Eliminada!',
                                    'La promoción ha sido removida.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire(
                                    'Error',
                                    'No se pudo eliminar la promoción',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Manejar eliminación de productos
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let productId = $(this).data('id');

                Swal.fire({
                    icon: 'info',
                    title: 'Eliminar',
                    text: '¿Estás seguro de que quieres eliminar este producto?',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/productos/${productId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function() {
                                Swal.fire('¡Eliminado!', 'El producto ha sido eliminado.', 'success');
                                table.ajax.reload();
                            },
                            error: function() {
                                Swal.fire('Error', 'No se pudo eliminar el producto.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
