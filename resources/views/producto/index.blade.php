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
                        <button class="btn btn-warning btn-sm" id="btnPromociones">
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
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal para promociones -->
<div class="modal fade" id="promocionModal" tabindex="-1" aria-labelledby="promocionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formPromocion" method="POST" action="{{ route('promociones.guardar') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="promocionModalLabel">Asignar Promociones</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="filtro">Filtrar por:</label>
            <select class="form-select" id="filtro" name="filtro">
              <option value="categoria">Categoría</option>
              <option value="proveedor">Proveedor</option>
            </select>
          </div>

          <div class="mb-3" id="selector-dinamico">
            <!-- Aquí se insertarán las categorías o proveedores vía JavaScript -->
          </div>

          <div class="mb-3">
            <label for="tipo_promocion">Tipo de Promoción</label>
            <select name="tipo_promocion" class="form-select" required>
              <option value="2x1">2x1</option>
              <option value="3x2">3x2</option>
              <option value="50%">50% de descuento</option>
              <option value="Precio especial">Precio especial</option>
              <option value="Segunda unidad al 30%">Segunda unidad al 30%</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar Promoción</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>


@stop
@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="DataTables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
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

            // Confirmación para eliminar
            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                let productId = $(this).data('id');

                Swal.fire({
                    icon: 'info',
                    title: 'Eliminar',
                    text: '¿Estás seguro de que quieres eliminar este producto?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
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
                            success: function () {
                                Swal.fire('¡Eliminado!', 'El producto ha sido eliminado.', 'success');
                                $('#tblProducts').DataTable().ajax.reload();
                            },
                            error: function () {
                                Swal.fire('Error', 'No se pudo eliminar el producto.', 'error');
                            }
                        });
                    }
                });
            });

            // Botón promociones: abrir modal
            $('#btnPromociones').on('click', function () {
                $('#promocionModal').modal('show');
            });

            // Cargar categorías o proveedores dinámicamente
            const filtro = document.getElementById('filtro');
            const selectorDinamico = document.getElementById('selector-dinamico');

            filtro.addEventListener('change', async function () {
                const tipo = this.value;
                selectorDinamico.innerHTML = '<p>Cargando...</p>';

                let url = tipo === 'categoria' ? '/api/categorias' : '/api/proveedores';

                try {
                    const response = await fetch(url);
                    const data = await response.json();

                    let html = `<label for="elemento">${tipo.charAt(0).toUpperCase() + tipo.slice(1)}</label>
                                <select name="${tipo}_id" class="form-select" required>`;

                    data.forEach(item => {
                        html += `<option value="${item.id}">${item.nombre}</option>`;
                    });

                    html += '</select>';
                    selectorDinamico.innerHTML = html;
                } catch (error) {
                    selectorDinamico.innerHTML = `<div class="alert alert-danger">Error al cargar datos</div>`;
                    console.error(error);
                }
            });

            // Carga inicial
            filtro.dispatchEvent(new Event('change'));
        });
    </script>
@endsection

