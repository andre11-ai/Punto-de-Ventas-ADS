@extends('adminlte::page')

@section('title', 'Facturación')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0"><i class="fas fa-file-invoice-dollar me-2"></i>Facturación</h1>

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
        #tblFacturacion {
            width: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #tblFacturacion thead th {
            background-color: #3490dc;
            color: white;
            font-weight: 500;
            vertical-align: middle;
            padding: 0.75rem 1rem;
        }

        #tblFacturacion tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        #tblFacturacion tbody tr:hover {
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

        /* Estilos para alertas */
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

        /* Estilos para paginación */
        .dataTables_length {
            margin: 1rem 0;
        }

        .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dataTables_length select {
            width: auto;
            display: inline-block;
            margin: 0 0.5rem;
        }

        .dataTables_info {
            padding-top: 1rem !important;
        }

        .dataTables_paginate {
            padding-top: 1rem !important;
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
            <div class="card-body p-0">
                            <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span id="card_title">
                        <i class="fas fa-file-invoice-dollar me-2"></i>{{ __('Facturación') }}
                    </span>
                </div>
            </div>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3">
                        <i class="fas fa-check-circle me-2"></i><strong>{{ $message }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('success') && session('open_pdf'))
                <div class="modal fade show" id="facturaSuccessModal" tabindex="-1" style="display:block; background:rgba(0,0,0,0.35)">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">¡Éxito!</h5>
                    </div>
                    <div class="modal-body">
                        <p>{{ session('success') }}</p>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ session('open_pdf') }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-print"></i> Descargar Ticket
                        </a>
                        <button type="button" class="btn btn-secondary" id="btnRecargar">
                        Aceptar
                        </button>
                    </div>
                    </div>
                </div>
                </div>
                <script>
                document.getElementById('btnRecargar').onclick = function() {
                    window.location.href = "{{ route('factura.index') }}";
                };
                document.getElementById('facturaSuccessModal').onclick = function(e) {
                    if(e.target === this) this.style.display = 'none';
                };
                </script>
                @endif

                <div class="table-responsive p-3">
                    <table class="table table-striped table-hover display responsive nowrap" width="100%" id="tblFacturacion">
                        <thead>
                            <tr>
                                <th class="text-center"><i class="fas fa-hashtag me-1"></i>ID Venta</th>
                                <th><i class="fas fa-user me-1"></i>Cliente</th>
                                <th class="text-end"><i class="fas fa-dollar-sign me-1"></i>Total</th>
                                <th class="text-center"><i class="fas fa-calendar-alt me-1"></i>Fecha</th>
                                <th class="text-center"><i class="fas fa-cogs me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventas as $venta)
                            <tr>
                                <td class="text-center fw-bold">#{{ $venta->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle me-2 text-muted"></i>
                                        <span>{{ $venta->cliente->nombre ?? 'Sin cliente' }}</span>
                                    </div>
                                </td>
                                <td class="text-end fw-bold text-primary">${{ number_format($venta->total, 2) }}</td>
                                <td class="text-center">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        @if($venta->factura)
                                            <a href="{{ route('factura.show', $venta->factura->id) }}"
                                               class="btn btn-info btn-sm btn-action"
                                               title="Ver factura">
                                                <i class="fas fa-eye me-1"></i> Ver
                                            </a>
                                        @else
                                            <a href="{{ route('factura.create', $venta->id) }}"
                                               class="btn btn-success btn-sm btn-action"
                                               title="Generar factura">
                                                <i class="fas fa-file-invoice me-1"></i> Generar
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
            $('#tblFacturacion').DataTable({
                responsive: true,
                fixedHeader: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                },
                order: [[0, 'desc']],
                dom: '<"top"<"d-flex justify-content-between align-items-center"lf>>rt<"bottom"<"d-flex justify-content-between align-items-center"ip>><"clear">',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control form-control-sm');
                    $('.dataTables_length select').addClass('form-control form-control-sm');
                }
            });
        });
    </script>
@endsection
