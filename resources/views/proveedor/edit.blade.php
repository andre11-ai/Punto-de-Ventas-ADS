@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="">
        <div class="col-md-12">

            @includeif('partials.errors')

            <div class="card card-default">
                <div class="card-header">
                    <span class="card-title">{{ __('Update') }} Categoria</span>
                </div>
<form action="{{ route('proveedores.update', $proveedor->id) }}" method="POST">
    @csrf
    @method('PUT')
    @include('proveedor.form')
</form>
                </div>
            </div>
        </div>
    </div>
@stop
