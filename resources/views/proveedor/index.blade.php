@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1><i class="fas fa-truck me-2"></i>Proveedores</h1>
@stop

@section('css')
    <link href="DataTables/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        #tblproveedores {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #tblproveedores thead th {
            background-color: #3490dc;
            color: white;
            font-weight: 500;
        }

        #tblproveedores tbody tr:hover {
            background-color: rgba(52, 144, 220, 0.1);
        }

        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
        }

        .card-header {
            background-color: #3490dc !important;
            color: white !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .alert-success {
            border-left: 4px solid #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }

        .alert-success strong {
            color: #28a745;
        }

        .btn-close {
            filter: invert(1);
            opacity: 0.8;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
                .shortcut-hint {
         opacity: 0.9;
          font-size: 0.8em;
          margin-left: 0.5em;
          color: #bbb;
        }

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            <i class="fas fa-truck-loading me-2"></i>{{ __('Proveedores') }}
                        </span>

                        <div class="float-right">
                            <a id="new-provider-button" href="{{ route('proveedores.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus-circle me-1"></i>{{ __('Nuevo Proveedor') }}
                            </a>
                            <span class="shortcut-hint">(F1)</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover display responsive nowrap" width="100%" id="tblproveedores">
                            <thead class="thead">
                                <tr>
                                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                    <th><i class="fas fa-building me-1"></i>Nombre</th>
                                    <th><i class="fas fa-barcode me-1"></i>UPC</th>
                                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($proveedores as $proveedor)
                                    <tr tabindex="0" class="provider-row">
                                        <td>{{ $proveedor->id }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $proveedor->nombre }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $proveedor->upc }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a class="btn btn-sm btn-primary btn-action btn-edit" href="{{ route('proveedores.edit', $proveedor->id) }}">
                                                    <i class="fas fa-edit me-1"></i>Editar
                                                </a>

                                                <form id="delete-form-{{ $proveedor->id }}" action="{{ route('proveedores.destroy', $proveedor->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger btn-action btn-delete" data-id="{{ $proveedor->id }}">
                                                        <i class="fas fa-trash-alt me-1"></i>Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="empty-state">
                                            <i class="fas fa-box-open"></i>
                                            <h5 class="mt-2">No hay proveedores registrados</h5>
                                            <p class="text-muted">Comienza agregando un nuevo proveedor</p>
                                            <a href="{{ route('proveedores.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus me-1"></i>Agregar Proveedor
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Botón flotante de atajos -->
<button id="shortcuts-button" title="Ver atajos" style="
    position:fixed; bottom:20px; right:20px;
    background-color:purple; color:white; border:none; border-radius:50%;
    width:48px; height:48px; font-size:1.2em; cursor:pointer; z-index:1000;">
  F12
</button>

<!-- Modal de atajos -->
<div id="shortcuts-modal" class="modal" style="
    display:none; position:fixed; top:0; left:0; right:0; bottom:0;
    background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:1001;">
  <div class="modal-content" style="
      background:white; padding:1.5em; border-radius:8px; max-width:400px; margin:auto;">
    <h2>Atajos de Teclado</h2>
    <ul>
      <li><strong>F1</strong> – Nuevo Proveedor</li>
      <li><strong>F2</strong> – Buscar Proveedores</li>
      <li><strong>F3</strong> – Página Anterior</li>
      <li><strong>F4</strong> – Página Siguiente</li>
      <li><strong>F5</strong> – Selector de filas</li>
      <li><strong>F6</strong> – Editar Proveedor Seleccionado</li>
      <li><strong>F7</strong> – Eliminar Proveedor Seleccionado</li>
      <li><strong>F12</strong> – Mostrar/Cerrar ayuda de atajos</li>
    </ul>
    <button class="close-modal" style="margin-top:1em;">Cerrar</button>
  </div>
</div>

@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
  // Resaltar fila seleccionada
  $('#tblproveedores').on('focus', 'tbody tr.provider-row', function() {
    $(this).addClass('selected').siblings().removeClass('selected');
  });

  // Atajos de teclado F1–F7 y F12
  document.addEventListener('keydown', function(e) {
    const tag = document.activeElement.tagName;
    if (tag === 'INPUT' || tag === 'TEXTAREA') return;

    switch (e.key) {
      case 'F1': // Nuevo Proveedor
        e.preventDefault();
        document.getElementById('new-provider-button').click();
        break;
      case 'F2': // Buscar
        e.preventDefault();
        document.getElementById('filter-input').focus();
        break;
      case 'F3': // Página Anterior
        e.preventDefault();
        document.getElementById('prev-page-button').click();
        break;
      case 'F4': // Página Siguiente
        e.preventDefault();
        document.getElementById('next-page-button').click();
        break;
      case 'F5': // Selector de filas
        e.preventDefault();
        document.getElementById('length-select').focus();
        break;
      case 'F6': // Editar seleccionado
        e.preventDefault();
        const selEdit = document.querySelector('#tblproveedores tbody tr.selected');
        if (selEdit) selEdit.querySelector('.btn-edit').click();
        break;
      case 'F7': // Eliminar seleccionado
        e.preventDefault();
        const selDel = document.querySelector('#tblproveedores tbody tr.selected');
        if (selDel) selDel.querySelector('.btn-delete').click();
        break;
      case 'F12': // Modal de ayuda
        e.preventDefault();
        const modal = document.getElementById('shortcuts-modal');
        modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
        break;
    }
  });

  // Toggle modal con el botón morado y cerrar
  document.getElementById('shortcuts-button')
    .addEventListener('click', () => {
      const modal = document.getElementById('shortcuts-modal');
      modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
    });
  document.querySelector('#shortcuts-modal .close-modal')
    .addEventListener('click', () => {
      document.getElementById('shortcuts-modal').style.display = 'none';
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="DataTables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Configuración de DataTables
            const table = new DataTable('#tblproveedores', {
                responsive: true,
                fixedHeader: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                },
                order: [[0, 'desc']],
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control form-control-sm');
                    // 1) Campo buscar (F2)
  $('#tblproveedores_filter input')
     .addClass('form-control form-control-sm')
     .attr('id', 'filter-input')
     .after('<span class="shortcut-hint">(F2)</span>');
   // 2) Botones de paginación (F3 / F4)
   $('#tblproveedores_paginate .previous')
     .attr('id', 'prev-page-button')
     .after('<span class="shortcut-hint">(F3)</span>');
   $('#tblproveedores_paginate .next')
     .attr('id', 'next-page-button')
     .after('<span class="shortcut-hint">(F4)</span>');
   // 3) Selector de longitud (F5)
   $('#tblproveedores_length select')
     .attr('id', 'length-select')
     .after('<span class="shortcut-hint">(F5)</span>');
                }
            });

            // Eliminar proveedor con SweetAlert
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const proveedorId = $(this).data('id');
                const form = $(this).closest('form');

                Swal.fire({
                    title: '¿Eliminar proveedor?',
                    text: "¡Esta acción no se puede deshacer!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    backdrop: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                return response;
                            },
                            error: function(xhr) {
                                Swal.showValidationMessage(
                                    `Error: ${xhr.statusText}`
                                );
                            }
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            '¡Eliminado!',
                            'El proveedor ha sido eliminado.',
                            'success'
                        ).then(() => {
                            table.row(form.parents('tr')).remove().draw();
                        });
                    }
                });
            });
        });
    </script>
@endsection
