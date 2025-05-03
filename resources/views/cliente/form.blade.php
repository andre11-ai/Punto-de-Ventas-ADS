<div class="box box-info padding-1">
    <div class="box-body row">
        <div class="form-group col-md-4">
            {!! html()->label('Nombre')->for('nombre') !!}
            {!! html()->text('nombre', $cliente->nombre)
                ->class('form-control' . ($errors->has('nombre') ? ' is-invalid' : ''))
                ->placeholder('Nombre') !!}
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-3">
            {!! html()->label('Teléfono')->for('telefono') !!}
            {!! html()->text('telefono', $cliente->telefono)
                ->class('form-control' . ($errors->has('telefono') ? ' is-invalid' : ''))
                ->placeholder('Teléfono') !!}
            @error('telefono')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-5">
            {!! html()->label('Dirección')->for('direccion') !!}
            {!! html()->text('direccion', $cliente->direccion)
                ->class('form-control' . ($errors->has('direccion') ? ' is-invalid' : ''))
                ->placeholder('Dirección') !!}
            @error('direccion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="box-footer mt20 text-right">
        {!! html()->a('/clientes', __('Cancel'))->class('btn btn-danger') !!}
        {!! html()->button(__('Submit'))
            ->type('submit')
            ->class('btn btn-primary') !!}
    </div>
</div>
