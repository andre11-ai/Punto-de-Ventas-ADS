@extends('adminlte::page')

@section('title', 'Generar Factura')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0"><i class="fas fa-file-invoice me-2"></i>Generar Factura Venta #{{ $venta->id }}</h1>
    </div>
@stop

@section('css')
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

        /* Estilos para formulario */
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .form-control {
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            padding: 0.375rem 0.75rem;
        }

        .form-control:focus {
            border-color: #3490dc;
            box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25);
        }

        /* Estilos para alertas */
        .alert {
            border-radius: 0.5rem;
            border-left: 4px solid;
        }

        .alert-danger {
            border-left-color: #dc3545;
        }

        .alert-danger strong {
            color: #dc3545;
        }

        .alert ul {
            margin-bottom: 0;
            padding-left: 1.5rem;
        }

        /* Estilos para botones */
        .btn-primary {
            background-color: #3490dc;
            border-color: #2a7aaf;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: #2a7aaf;
            border-color: #226089;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
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
                        <i class="fas fa-file-invoice me-2"></i>{{ __('Generar Factura') }}
                    </span>
                </div>
            </div>

            <div class="card-body">
                {{-- Errores de validación --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Error en el formulario</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>{{ session('error') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('factura.store') }}">
                    @csrf
                    <input type="hidden" name="venta_id" value="{{ $venta->id }}">

                    <div class="mb-4">
                        <label for="rfc" class="form-label">
                            <i class="fas fa-id-card me-1"></i>RFC
                        </label>
                        <input type="text" name="rfc" class="form-control" required value="{{ old('rfc') }}"
                               placeholder="Ingrese el RFC">
                    </div>

                    <div class="mb-4">
                        <label for="razon_social" class="form-label">
                            <i class="fas fa-building me-1"></i>Razón Social
                        </label>
                        <input type="text" name="razon_social" class="form-control" required
                               value="{{ old('razon_social') }}" placeholder="Ingrese la razón social">
                    </div>

                    <div class="mb-4">
                        <label for="uso_cfdi" class="form-label">
                            <i class="fas fa-file-code me-1"></i>Uso CFDI
                        </label>
                        <input type="text" name="uso_cfdi" class="form-control" required
                               placeholder="G03, P01, etc." value="{{ old('uso_cfdi') }}">
                        <small class="text-muted">Ejemplos: G03 (Gastos en general), P01 (Por definir)</small>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-primary">
                            <i class="fas fa-file-invoice me-1"></i> Generar Factura
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
