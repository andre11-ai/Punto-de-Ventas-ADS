<div class="box box-info padding-1">
    <div class="box-body row">

        <!-- Campo Nombre -->
        <div class="form-group col-md-12">
            {!! html()->label('Nombre')->for('nombre') !!}
            {!! html()->text('nombre', $proveedor->nombre ?? '')
                ->class('form-control' . ($errors->has('nombre') ? ' is-invalid' : ''))
                ->placeholder('Nombre') !!}
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo UPC -->
        <div class="form-group col-md-12">
            {!! html()->label('Código UPC')->for('upc') !!}
            {!! html()->text('upc', $proveedor->upc ?? '')
                ->class('form-control' . ($errors->has('upc') ? ' is-invalid' : ''))
                ->placeholder('Ej: 123456789012') !!}
            @error('upc')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    </div>
</div>

<div class="box-footer mt20 text-right">
    {!! html()->a('/proveedores', __('Cancel'))->class('btn btn-danger') !!}
    {!! html()->button(__('Submit'))->type('submit')->class('btn btn-primary') !!}
</div>
