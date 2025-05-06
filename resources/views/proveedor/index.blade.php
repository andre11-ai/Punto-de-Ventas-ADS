@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Proveedores</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            {{ __('Proveedores') }}
                        </span>

                        <div class="float-right">
                            <a href="{{ route('proveedores.create') }}" class="btn btn-primary btn-sm float-right"
                                data-placement="left">
                                {{ __('Create New') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-hover display responsive nowrap" width="100%" id="tblproveedores">
                            <thead class="thead">
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                     <th>UPC</th>
                                    <th>Acciones</th>

                                </tr>
                            </thead>
                            <tbody>
    @forelse ($proveedores as $proveedor)
        <tr>
            <td>{{ $proveedor->id }}</td>
            <td>{{ $proveedor->nombre }}</td>
             <td>{{ $proveedor->upc }}</td>
            <td>
                <a class="btn btn-sm btn-primary" href="{{ route('proveedores.edit', $proveedor->id) }}">Editar</a>

<form id="delete-form-{{ $proveedor->id }}" action="{{ route('proveedores.destroy', $proveedor->id) }}" method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
<button class="btn btn-danger btn-sm btn-delete" data-id="{{ $proveedor->id }}">
    Eliminar
</button>
</form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No hay proveedores registrados.</td>
        </tr>
    @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="DataTables/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection
@section('js')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="DataTables/datatables.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let table = new DataTable('#tblproveedores', {
        responsive: true,
        fixedHeader: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
        },
        order: [[0, 'desc']]
    });

    // Evento SweetAlert2 para eliminar
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        let proveedorId = $(this).data('id');

        Swal.fire({
            icon: 'info',
            title: 'Eliminar',
            text: '¿Estás seguro de que quieres eliminar este proveedor?',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/proveedores/${proveedorId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        Swal.fire('¡Eliminado!', 'El proveedor ha sido eliminado.', 'success');
                        // Recargar la tabla sin recargar la página
                        $('#tblproveedores').DataTable().row($(e.target).parents('tr')).remove().draw();
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo eliminar el proveedor.', 'error');
                    }
                });
            }
        });
    });
});
</script>

@endsection

