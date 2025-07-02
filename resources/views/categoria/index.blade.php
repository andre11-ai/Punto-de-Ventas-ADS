@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1><i class="fas fa-folder me-2"></i>Categorías</h1>
@stop

@section('css')
    <link href="DataTables/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        #tblcategorias {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #tblcategorias thead th {
            background-color: #3490dc;
            color: white;
            font-weight: 500;
        }

        #tblcategorias tbody tr:hover {
            background-color: rgba(52, 144, 220, 0.1);
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border-radius: 4px;
        }

        .card-header {
            background-color: #3490dc !important;
            color: white;
        }

        .btn-primary {
            background-color: #3490dc;
            border-color: #2a7aaf;
        }

        .btn-primary:hover {
            background-color: #2a7aaf;
            border-color: #226089;
        }

        .alert-success {
            border-left: 4px solid #28a745;
        }

        .alert-success strong {
            color: #28a745;
        }

        .btn-close {
            filter: invert(1);
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
                            <i class="fas fa-folder-open me-2"></i>{{ __('Categorías') }}
                        </span>

                        <div class="float-right">
                            <a href="{{ route('categorias.create') }}" class="btn btn-success btn-sm float-right"
                                data-placement="left">
                                <i class="fas fa-plus-circle me-1"></i>{{ __('Nueva Categoría') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i><strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover display responsive nowrap" width="100%" id="tblcategorias">
                            <thead class="thead">
                                <tr>
                                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                    <th><i class="fas fa-tag me-1"></i>Nombre</th>
                                    <th><i class="fas fa-truck me-1"></i>Proveedor</th>
                                    <th><i class="fas fa-barcode me-1"></i>UPC</th>
                                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categorias as $categoria)
                                    <tr>
                                        <td>{{ $categoria->id }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <i class="fas fa-folder me-1"></i>{{ $categoria->nombre }}
                                            </span>
                                        </td>
                                        <td>{{ $categoria->proveedor->nombre ?? '-' }}</td>
                                        <td>{{ $categoria->proveedor->upc ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a class="btn btn-sm btn-primary btn-action" href="{{ route('categorias.edit', $categoria->id) }}">
                                                    <i class="fas fa-edit me-1"></i>Editar
                                                </a>

                                                <form id="delete-form-{{ $categoria->id }}" action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm btn-action btn-delete" data-id="{{ $categoria->id }}">
                                                        <i class="fas fa-trash-alt me-1"></i>Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                            <h5 class="text-muted">No hay categorías registradas</h5>
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
    <!-- Botón flotante de atajos: F12 -->
<button id="shortcuts-button" title="Ver atajos" style="
    position: fixed; bottom: 20px; right: 20px;
    background-color: purple; color: white; border: none; border-radius: 50%;
    width: 48px; height: 48px; font-size: 1.2em; cursor: pointer; z-index: 1000;">
  F12
</button>

<!-- Modal de atajos -->
<div id="shortcuts-modal" class="modal" style="
    display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 1001;">
  <div class="modal-content" style="
      background: white; padding: 1.5em; border-radius: 8px; max-width: 400px; margin: auto;">
    <h2>Atajos de Teclado</h2>
    <ul>
      <li><strong>F1</strong> – Nueva Categoría</li>
      <li><strong>F3</strong> – Selector de cantidad</li>
      <li><strong>F4</strong> – Editar categoría seleccionada</li>
      <li><strong>F5</strong> – Eliminar categoría seleccionada</li>
      <li><strong>F7</strong> – Buscar Categoria</li>
      <li><strong>F12</strong> – Mostrar/Cerrar ayuda de atajos</li>
    </ul>
    <button class="close-modal" style="margin-top: 1em;">Cerrar</button>
  </div>
</div>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('keydown', function(e) {
    // Evitar atajos si el usuario está en un input o textarea
    if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;

    switch (e.key) {
        case 'F1': // Nuevo Categoría
            e.preventDefault();
            // Suponemos que el botón de "Nueva Categoría" está en la cabecera y lo podemos acceder con un selector, por ejemplo:
            document.querySelector('a[href*="/categorias/create"]').click();
            break;
        case 'F3': // Enfocar el selector de cantidad (length-select)
            e.preventDefault();
            document.getElementById('length-select').focus();
            break;
            case 'F4': // Editar categoría seleccionada
                    e.preventDefault();
                    const selEdit = document.querySelector('#tblcategorias tbody tr.selected');
                    if (selEdit) {
                        const btnEdit = selEdit.querySelector('.btn-edit');
                        if (btnEdit) btnEdit.click();
                    }
                    break;
                case 'F5': // Eliminar categoría seleccionada
                    e.preventDefault();
                    const selDel = document.querySelector('#tblcategorias tbody tr.selected');
                    if (selDel) {
                        const btnDelete = selDel.querySelector('.btn-delete');
                        if (btnDelete) btnDelete.click();
                    }
        case 'F7': // Enfocar el cuadro de búsqueda (filter-input)
            e.preventDefault();
            document.getElementById('filter-input').focus();
            break;
        case 'F12': // Mostrar/Cerrar modal de atajos
            e.preventDefault();
            const modal = document.getElementById('shortcuts-modal');
            modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
            break;
    }
});
    </script>
    <script>
  // Función para mostrar o cerrar el modal de atajos con F12 y el botón flotante
  document.getElementById('shortcuts-button').addEventListener('click', () => {
      const modal = document.getElementById('shortcuts-modal');
      modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
  });

  document.querySelector('#shortcuts-modal .close-modal').addEventListener('click', () => {
      document.getElementById('shortcuts-modal').style.display = 'none';
  });

</script>
    <script src="DataTables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Configuración de DataTables
            const table = new DataTable('#tblcategorias', {
                responsive: true,
                fixedHeader: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                },
                order: [[0, 'desc']],
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control form-control-sm');
                     $('.dataTables_filter input')
        .addClass('form-control form-control-sm')
        .attr('id', 'filter-input')
        .after('<span class="shortcut-hint">(F7)</span>');
    // Agregar id "length-select" y pista de atajo (F3) al selector de cantidad
    $('#tblcategorias_length select')
        .attr('id', 'length-select')
        .after('<span class="shortcut-hint">(F3)</span>');
                }
            });

            // Eliminar categoría con SweetAlert
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const categoriaId = $(this).data('id');
                const form = $(this).closest('form');

                Swal.fire({
                    title: '¿Eliminar categoría?',
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
                            '¡Eliminada!',
                            'La categoría ha sido eliminada.',
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
