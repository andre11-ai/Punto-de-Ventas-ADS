@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1><i class="fas fa-users me-2"></i>Usuarios</h1>
@stop

@section('css')
    <link href="DataTables/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        #tblUsers {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #tblUsers thead th {
            background-color: #3490dc;
            color: white;
            font-weight: 500;
        }

        #tblUsers tbody tr:hover {
            background-color: rgba(52, 144, 220, 0.1);
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border-radius: 4px;
        }

        .card-header {
            background-color: #3490dc !important;
            color: white !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
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

        .badge-role {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        .badge-admin {
            background-color: #dc3545;
            color: white;
        }

        .badge-user {
            background-color: #28a745;
            color: white;
        }

        .badge-manager {
            background-color: #fd7e14;
            color: white;
        }
        .shortcut-hint {
         opacity: 0.4;
         font-size: 0.8em;
         margin-left: 0.5em;
         color: #444;
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
            <i class="fas fa-user-friends me-2"></i>{{ __('Usuarios') }}
          </span>
          <div class="float-right">
            <a id="new-user-button" href="{{ route('usuarios.create') }}" class="btn btn-success btn-sm">
              <i class="fas fa-plus-circle me-1"></i>{{ __('Nuevo Usuario') }}
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
          <table id="tblUsers" class="table table-striped table-hover display responsive nowrap" width="100%">
            <thead class="thead">
              <tr>
                <th><i class="fas fa-hashtag me-1"></i>ID</th>
                <th><i class="fas fa-user me-1"></i>Nombre</th>
                <th><i class="fas fa-envelope me-1"></i>Correo</th>
                <th><i class="fas fa-clock me-1"></i>Turno</th>       {{-- <-- Nueva columna --}}
                <th><i class="fas fa-user-tag me-1"></i>Rol</th>
                <th><i class="fas fa-cogs me-1"></i>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($usuarios as $usuario)
                <tr tabindex="0" class="user-row">
                  <td>{{ $usuario->id }}</td>
                  <td>{{ $usuario->name }}</td>
                  <td>{{ $usuario->email }}</td>
                  <td>                                           {{-- ← Nueva celda Turno --}}
                    @if($usuario->turno === 'Matutino')
                      <span class="badge bg-info">Matutino</span>
                    @elseif($usuario->turno === 'Vespertino')
                      <span class="badge bg-warning">Vespertino</span>
                    @else
                      <span class="badge bg-secondary">Mixto</span>
                    @endif
                  </td>
                  <td>
                    @php
                      $badgeClass = '';
                      switch(strtolower($usuario->rol)) {
                        case 'admin': $badgeClass = 'badge-admin'; break;
                        case 'user': $badgeClass = 'badge-user'; break;
                        case 'manager': $badgeClass = 'badge-manager'; break;
                        default: $badgeClass = 'bg-secondary';
                      }
                    @endphp
                    <span class="badge {{ $badgeClass }} badge-role">
                      {{ $usuario->rol }}
                    </span>
                  </td>
                  <td>
                    <div class="d-flex gap-2">
                      <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-primary btn-action btn-edit">
                        <i class="fas fa-edit me-1"></i>Editar
                      </a>
                      <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="form-eliminar">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger btn-action btn-delete">
                          <i class="fas fa-trash-alt me-1"></i>Eliminar
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="empty-state">
                    <i class="fas fa-user-slash fa-2x"></i>
                    <h5 class="mt-3">No hay usuarios registrados</h5>
                    <p class="text-muted">Comienza agregando un nuevo usuario</p>
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary mt-2">
                      <i class="fas fa-plus me-1"></i>Agregar Usuario
                    </a>
                  </td>
                </tr>
                +                <tr>
                  <td colspan="6" class="empty-state"> {{-- Aumentamos a 6 --}}
                    <i class="fas fa-user-slash fa-2x"></i>
                    <h5 class="mt-3">No hay usuarios registrados</h5>
                    <p class="text-muted">Comienza agregando un nuevo usuario</p>
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary mt-2">
                      <i class="fas fa-plus me-1"></i>Agregar Usuario
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
<!-- Botón flotante -->
<button id="shortcuts-button" title="Ver atajos" style="
    position:fixed; bottom:20px; right:20px;
    background-color:purple; color:white; border:none; border-radius:50%;
    width:48px; height:48px; font-size:1.2em; cursor:pointer; z-index:1000;">
  F12
</button>
<!-- Modal -->
<div id="shortcuts-modal" class="modal" style="
    display:none; position:fixed; top:0; left:0; right:0; bottom:0;
    background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:1001;">
  <div class="modal-content" style="
      background:white; padding:1.5em; border-radius:8px; max-width:400px; margin:auto;">
    <h2>Atajos de Teclado</h2>
    <ul>
      <li><strong>F1</strong> – Nuevo Usuario</li>
      <li><strong>F2</strong> – Buscar Usuarios</li>
      <li><strong>F3</strong> – Página Anterior</li>
      <li><strong>F4</strong> – Página Siguiente</li>
      <li><strong>F5</strong> – Selector de filas</li>
      <li><strong>F6</strong> – Editar Usuario Seleccionado</li>
      <li><strong>F7</strong> – Eliminar Usuario Seleccionado</li>
      <li><strong>F12</strong> – Mostrar/Cerrar ayuda de atajos</li>
    </ul>
    <button class="close-modal" style="margin-top:1em;">Cerrar</button>
  </div>
</div>
@stop

@section('js')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  // 1) Resaltar la fila “seleccionada” al enfocarla con Tab o clic
  $('#tblUsers').on('focus', 'tbody tr.user-row', function() {
    $(this).addClass('selected').siblings().removeClass('selected');
  });

  // 2) Atajos de teclado F1–F8
  document.addEventListener('keydown', function(e) {
    const tag = document.activeElement.tagName;
    if (tag === 'INPUT' || tag === 'TEXTAREA') return;

    switch (e.key) {
      // F1 – Nuevo Usuario
      case 'F1':
        e.preventDefault();
        document.getElementById('new-user-button').click();
        break;

      // F2 – Enfocar campo de búsqueda
      case 'F2':
        e.preventDefault();
        document.getElementById('filter-input').focus();
        break;

      // F3 – Página Anterior
      case 'F3':
        e.preventDefault();
        document.getElementById('prev-page-button').click();
        break;

      // F4 – Página Siguiente
      case 'F4':
        e.preventDefault();
        document.getElementById('next-page-button').click();
        break;

      // F5 – Selector de número de filas
      case 'F5':
        e.preventDefault();
        document.getElementById('length-select').focus();
        break;

      // F6 – Editar Usuario Seleccionado
      case 'F6':
        e.preventDefault();
        const selEdit = document.querySelector('#tblUsers tbody tr.selected');
        if (selEdit) selEdit.querySelector('.btn-edit').click();
        break;

      // F7 – Eliminar Usuario Seleccionado
      case 'F7':
        e.preventDefault();
        const selDel = document.querySelector('#tblUsers tbody tr.selected');
        if (selDel) selDel.querySelector('.btn-delete').click();
        break;

      // F8 – Mostrar/Cerrar modal de atajos
      case 'F12':
        e.preventDefault();
        const modal = document.getElementById('shortcuts-modal');
        modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
        break;
    }
  });

  // 3) Toggle de modal al hacer click en el botón morado
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
      const table = new DataTable('#tblUsers', {
        responsive: true,
        fixedHeader: true,
        language: {
          url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'desc']],
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        initComplete: function() {
          $('.dataTables_filter input').addClass('form-control form-control-sm');
          $('#tblUsers_filter input')
  .attr('id', 'filter-input')
  .after('<span class="shortcut-hint">(F2)</span>');
$('#tblUsers_paginate .previous')
  .attr('id', 'prev-page-button')
  .after('<span class="shortcut-hint">(F3)</span>');
$('#tblUsers_paginate .next')
  .attr('id', 'next-page-button')
  .after('<span class="shortcut-hint">(F4)</span>');
  $('#tblUsers_length select')
  .attr('id', 'length-select')
  .after('<span class="shortcut-hint">(F5)</span>');
        }
      });

      // Eliminar usuario con SweetAlert
      $(document).on('submit', '.form-eliminar', function(e) {
        e.preventDefault();
        const form = $(this);

        Swal.fire({
          title: '¿Eliminar usuario?',
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
              'El usuario ha sido eliminado.',
              'success'
            ).then(() => {
              table.row(form.parents('tr')).remove().draw();
            });
          }
        });
      });
    });
  </script>
@stop
