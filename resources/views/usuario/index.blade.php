@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="card">

      {{-- Encabezado de la tarjeta --}}
      <div class="card-header">
        <h3 class="card-title">{{ __('Usuarios') }}</h3>
        <div class="card-tools">
          {{-- Botón para crear nuevo usuario --}}
          <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm">
            {{ __('Create New') }}
          </a>
        </div>
      </div>
      {{-- Cuerpo de la tarjeta --}}
      <div class="card-body">

        {{-- Mensaje de éxito --}}
        @if ($message = Session::get('success'))
          <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>{{ $message }}</strong>
          </div>
        @endif

        {{-- Tabla de usuarios (se llena con DataTables por AJAX) --}}
        <div class="table-responsive">
          <table id="tblUsers" class="table table-bordered">
    <thead class="thead-light">
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->rol }}</td>
                    <td>
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-primary btn-sm">
                            Editar
                        </a>
                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="form-eliminar d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
        </div>

      </div> {{-- Fin del card-body --}}
    </div> {{-- Fin del card --}}
  </div> {{-- Fin del col --}}
</div> {{-- Fin del row --}}
@stop


@section('css')
  {{-- Estilos para DataTables y personalizados --}}
  <link href="DataTables/datatables.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop
@section('js')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="DataTables/datatables.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Inicializa DataTable y guarda la instancia para recargas
      window.usersTable = new DataTable('#tblUsers', {
        responsive: true,
        fixedHeader: true,
        ajax: {
          url: "{{ route('usuarios.list') }}",
          dataSrc: 'data'
        },
        columns: [
          { data: 'id'    },
          { data: 'name'  },   // mapea el campo "name"
          { data: 'email' },
          { data: 'rol'   },   // mapea el campo "rol"
          {
            data: null,
            orderable: false,
            searchable: false,
            render: function(row) {
              return `
                <a href="/usuarios/${row.id}/edit" class="btn btn-sm btn-primary mr-1">
                  Editar
                </a>
                <button class="btn btn-sm btn-danger" onclick="deleteUser(${row.id})">
                  Eliminar
                </button>
              `;
            }
          }
        ],
        language: {
          url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'desc']]
      });
    });

    function deleteUser(id) {
      Swal.fire({
        title: '¿Eliminar?',
        text: '¿Estás seguro de que quieres eliminar este cliente?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6', // Azul
        cancelButtonColor: '#d33',     // Rojo
        confirmButtonText: 'Si Eliminar',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
        popup: 'animated fadeIn'
        }
        }).then((result) => {
            if (result.isConfirmed) {
            fetch(/usuarios/${id}, {
                method: 'DELETE',
                headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
                }
            })
      .then(() => {
        Swal.fire({
          title: 'Eliminado',
          text: 'El usuario fue eliminado exitosamente.',
          icon: 'success',
          timer: 1500,
          showConfirmButton: false
        });
        window.usersTable.ajax.reload();
      })
      .catch(error => {
        console.error(error);
        Swal.fire({
          title: 'Error',
          text: 'Ocurrió un problema al eliminar el usuario.',
          icon: 'error'
        });
      });
            }
        });
    }
  </script>
 @section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.querySelectorAll('.form-eliminar');

            forms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // evita que se envíe el formulario inmediatamente

                    Swal.fire({
                        title: 'Eliminar',
                        text: '¿Estás seguro de que quieres eliminar este usuario?',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // si confirma, se envía el formulario
                        }
                    });
                });
            });
        });
    </script>
@endsection


@stop

