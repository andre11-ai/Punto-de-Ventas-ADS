<div class="box box-info padding-1">
    <div class="box-body row">
        <div class="form-group col-md-4">
            {!! html()->label('Código')->for('codigo') !!}
            {!! html()->text('codigo', $producto->codigo)
                ->class('form-control' . ($errors->has('codigo') ? ' is-invalid' : ''))
                ->placeholder('Código') !!}
            @error('codigo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-5">
            {!! html()->label('Producto')->for('producto') !!}
            {!! html()->text('producto', $producto->producto)
                ->class('form-control' . ($errors->has('producto') ? ' is-invalid' : ''))
                ->placeholder('Producto') !!}
            @error('producto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-3">
            {!! html()->label('Precio Compra')->for('precio_compra') !!}
            {!! html()->text('precio_compra', $producto->precio_compra)
                ->class('form-control' . ($errors->has('precio_compra') ? ' is-invalid' : ''))
                ->placeholder('Precio Compra') !!}
            @error('precio_compra')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-3">
            {!! html()->label('Precio Venta')->for('precio_venta') !!}
            {!! html()->text('precio_venta', $producto->precio_venta)
                ->class('form-control' . ($errors->has('precio_venta') ? ' is-invalid' : ''))
                ->placeholder('Precio Venta') !!}
            @error('precio_venta')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            {!! html()->label('Categoría')->for('id_categoria') !!}
            {!! html()->select('id_categoria', $categorias, $producto->id_categoria)
                ->class('form-control')
                ->placeholder('Selecciona una categoría') !!}
            @error('id_categoria')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-4">
            {!! html()->label('Foto')->for('foto') !!}
            {!! html()->file('foto')
                ->class('form-control' . ($errors->has('foto') ? ' is-invalid' : '')) !!}
            @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($producto->foto)
                <img src="{{ asset('storage/' . $producto->foto) }}" alt="Imagen actual" style="max-width: 100px; max-height: 100px;">
            @else
                <p>Sin imagen</p>
            @endif
        </div>
    </div>

    <div class="box-footer mt20 text-right">
        {!! html()->a('/productos', __('Cancel'))->class('btn btn-danger') !!}
        {!! html()->button(__('Submit'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
