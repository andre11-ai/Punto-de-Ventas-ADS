@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Venta</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            {{ __('Nueva venta') }}
                        </span>
                        <div class="float-right">
                            <a href="{{ route('venta.show') }}" class="btn btn-primary btn-sm float-right"
                                data-placement="left">
                                {{ __('Listar ventas') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @livewire('product-list')
                    <button class="btn btn-primary fixed-button" id="btnVenta" type="button">Generar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const btnVenta = document.querySelector('#btnVenta');
        document.addEventListener('DOMContentLoaded', function() {
            btnVenta.addEventListener('click', function() {
                Swal.fire({
                    title: "Mensaje?",
                    text: "Esta seguro de procesar la venta!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, procesar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('/venta', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => {
                                return response.json();
                            })
                            .then(data => {
                                Swal.fire({
                                    title: "Respuesta",
                                    text: data.title,
                                    icon: data.icon
                                });
                                if (data.icon == 'success') {
                                    setTimeout(() => {
                                        window.open('/venta/' + data.ticket +
                                            '/ticket', '_blank');
                                        window.location.reload();
                                    }, 1500);
                                }
                            })
                            .catch(error => {
                                console.error('Error: ', error);
                            });
                    }
                });
            })
        })
    </script>
@stop
