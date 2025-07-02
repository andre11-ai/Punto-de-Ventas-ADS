@extends('adminlte::page')

@section('title', 'Lista de Facturas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0"><i class="fas fa-file-invoice-dollar me-2"></i>Lista de Facturas</h1>
        <div class="d-flex align-items-center">
            <span class="badge bg-primary me-2">{{ $facturas->total() }} registros</span>
            <form method="GET" class="d-flex" style="gap: 0.5rem;">

            </form>
        </div>
    </div>
@stop

@section('css')
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos generales */
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: none;
        }

        .card-header {
            background-color: #3490dc !important;
            color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1rem 1.5rem;
        }

        /* Estilos para la tabla */
        #tblFacturas {
            width: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #tblFacturas thead th {
            background-color: #3490dc;
            color: white;
            font-weight: 500;
            vertical-align: middle;
            padding: 0.75rem 1rem;
        }

        #tblFacturas tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        #tblFacturas tbody tr:hover {
            background-color: rgba(52, 144, 220, 0.1);
        }

        /* Estilos para botones */
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background-color: #3490dc;
            border-color: #2a7aaf;
        }

        .btn-primary:hover {
            background-color: #2a7aaf;
            border-color: #226089;
        }

        /* Estilos para alertas y modal */
        .alert {
            border-radius: 0.5rem;
            border-left: 4px solid;
        }

        .alert-success {
            border-left-color: #28a745;
        }

        .alert-success strong {
            color: #28a745;
        }

        .modal-content {
            border-radius: 0.5rem;
        }

        .modal-header {
            background-color: #28a745;
            color: white;
        }

        /* Estilos para paginación */
        .pagination .page-item.active .page-link {
            background-color: #3490dc;
            border-color: #3490dc;
        }

        .pagination .page-link {
            color: #3490dc;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .input-group {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span id="card_title">
                        <i class="fas fa-file-invoice me-2"></i>{{ __('Lista de Facturas') }}
                    </span>
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
                    <table class="table table-striped table-hover" id="tblFacturas">
                        <thead>
                            <tr>
                                <th class="text-center"><i class="fas fa-hashtag me-1"></i>Folio</th>
                                <th><i class="fas fa-user me-1"></i>Cliente</th>
                                <th class="text-center"><i class="fas fa-calendar-alt me-1"></i>Fecha</th>
                                <th class="text-end"><i class="fas fa-dollar-sign me-1"></i>Total</th>
                                <th class="text-center"><i class="fas fa-cogs me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facturas as $factura)
                            <tr>
                                <td class="text-center fw-bold">{{ $factura->folio }}</td>
                                <td>{{ $factura->venta->cliente->nombre ?? $factura->razon_social }}</td>
                                <td class="text-center">{{ $factura->fecha }}</td>
                                <td class="text-end fw-bold text-primary">${{ number_format($factura->total, 2) }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">

                                        <a href="{{ route('factura.pdffactura', $factura->id) }}"
                                           class="btn btn-primary btn-sm btn-action"
                                           target="_blank"
                                           title="Descargar PDF">
                                            <i class="fas fa-file-pdf me-1"></i> PDF
                                        </a>
                                        <form action="{{ route('factura.destroy', $factura->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm btn-action"
                                                    onclick="return confirm('¿Seguro que deseas eliminar esta factura?')"
                                                    title="Eliminar">
                                                <i class="fas fa-trash-alt me-1"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="showing-entries-info">
                            Mostrando {{ $facturas->firstItem() }} a {{ $facturas->lastItem() }} de {{ $facturas->total() }} registros
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            {{ $facturas->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tblFacturas').DataTable({
                responsive: true,
                paging: false,
                searching: false,
                info: false,
                ordering: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                },
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control form-control-sm');
                }
            });
        });
    </script>
@endsection
