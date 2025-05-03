<div class="box box-info padding-1">
    <div class="box-body row">
        <div class="form-group col-md-12">
            {!! html()->label('Nombre')->for('nombre') !!}
            {!! html()->text('nombre', $categoria->nombre)
                ->class('form-control' . ($errors->has('nombre') ? ' is-invalid' : ''))
                ->placeholder('Nombre') !!}
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="box-footer mt20 text-right">
        {!! html()->a('/categorias', __('Cancel'))->class('btn btn-danger') !!}
        {!! html()->button(__('Submit'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
