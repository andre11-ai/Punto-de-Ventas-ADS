<div class="box box-info padding-1">
    <div class="box-body row">
<!-- Código -->
<!-- Código -->
<div class="form-group col-md-4">
    {!! html()->label('Código (13 dígitos)')->for('codigo') !!} <!-- Cambiado a 13 dígitos -->
    {!! html()->text('codigo', $producto->codigo ?? old('codigo'))
        ->class('form-control' . ($errors->has('codigo') ? ' is-invalid' : ''))
        ->id('codigo')
        ->attributes(['readonly' => 'readonly']) !!}
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

       <!-- SELECT de Categoría -->
<!-- SELECT de Categoría -->
<div class="form-group col-md-4">
    <label for="id_categoria">Categoría</label>
<select name="id_categoria" id="id_categoria" class="form-control">
    <option value="">-- Selecciona una categoría --</option>
    @foreach($categorias as $key => $value)
        <option value="{{ $key }}" {{ $producto->id_categoria == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
</div>

<!-- Campo Proveedor (visible solo para mostrar el nombre) -->
<div class="form-group col-md-4">
    <label for="proveedor_nombre">Proveedor</label>
<input type="text" name="proveedor" id="proveedor_nombre" class="form-control" readonly>
</div>

<!-- Campo Proveedor oculto para enviar ID -->
<input type="hidden" id="id_proveedor" name="id_proveedor">

     <!-- Código de Barras -->
<div class="form-group col-md-4">
    {!! html()->label('Código de Barras (EAN-13)')->for('codigo_barras') !!}
    {!! html()->text('codigo_barras', $producto->codigo_barras ?? old('codigo_barras'))
        ->class('form-control' . ($errors->has('codigo_barras') ? ' is-invalid' : ''))
        ->id('codigo_barras')
        ->attributes(['readonly' => 'readonly']) !!}
    @error('codigo_barras')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <div class="mt-2" id="barcode-container">
        @if(!empty($producto->codigo_barras))
            <svg id="barcode-svg"></svg>
            <small class="text-muted d-block mt-1">{{ $producto->codigo_barras }}</small>
        @else
            <div class="alert alert-info p-2">Seleccione una categoría</div>
        @endif
    </div>
</div>
<!-- Foto (solo un campo) -->
<div class="form-group col-md-4">
    {!! html()->label('Foto')->for('foto') !!}
    {!! html()->file('foto')
        ->class('form-control' . ($errors->has('foto') ? ' is-invalid' : '')) !!}
    @error('foto')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if(isset($producto) && $producto->foto)
        <img src="{{ asset('storage/'.$producto->foto) }}" alt="Imagen actual" class="img-thumbnail mt-2" style="max-height: 100px;">
    @else
        <p class="text-muted mt-2">Sin imagen</p>
    @endif
</div>


    </div>

    <div class="box-footer mt20 text-right">
        {!! html()->a('/productos', __('Cancel'))->class('btn btn-danger') !!}
        {!! html()->button(__('Submit'))->type('submit')->class('btn btn-primary') !!}
    </div>
</div>
@section('js')
<!-- Cargar la librería JsBarcode -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    console.log("Script de código de barras cargado");

    const categoriaSelect = document.getElementById('id_categoria');
    const barcodeContainer = document.getElementById('barcode-container');
    const codigoBarrasInput = document.getElementById('codigo_barras');

    function formatEAN13(value) {
        if (!value) return null;
        value = value.toString().replace(/\D/g, '').substring(0, 13).padStart(13, '0');
        if (value.length === 13) {
            const digits = value.split('').map(Number);
            const checkDigit = digits.pop();
            let sum = 0;
            digits.forEach((digit, index) => {
                sum += digit * (index % 2 === 0 ? 1 : 3);
            });
            const calculatedCheck = (10 - (sum % 10)) % 10;
            if (checkDigit !== calculatedCheck) {
                return value.substring(0, 12) + calculatedCheck;
            }
        }
        return value;
    }

    function generateBarcode(value) {
        try {
            const formattedValue = formatEAN13(value);
            if (!formattedValue) throw new Error("Código inválido");

            codigoBarrasInput.value = formattedValue;
            barcodeContainer.innerHTML = '<svg id="barcode-svg"></svg>';
            JsBarcode("#barcode-svg", formattedValue, {
                format: "EAN13",
                lineColor: "#000",
                width: 2,
                height: 60,
                displayValue: true,
                fontSize: 14,
                margin: 5
            });
        } catch (error) {
            barcodeContainer.innerHTML = `
                <div class="alert alert-danger p-2">
                    Error: ${error.message}<br>Valor: ${value || 'Ninguno'}
                </div>`;
        }
    }

    async function loadCategoryData(categoryId) {
        try {
            barcodeContainer.innerHTML = '<div class="alert alert-info p-2">Generando código...</div>';
            const response = await fetch(`/api/categoria-info/${categoryId}`);
            const data = await response.json();

            if (!data.success) throw new Error(data.error || 'Error en datos');

            document.getElementById('codigo').value = data.data.codigo || '';
            document.getElementById('codigo_barras').value = data.data.codigo_barras || '';
            document.getElementById('proveedor_nombre').value = data.data.proveedor?.nombre || '';
            document.getElementById('id_proveedor').value = data.data.proveedor?.id || '';

            if (data.data.codigo_barras) {
                generateBarcode(data.data.codigo_barras);
            }

        } catch (error) {
            barcodeContainer.innerHTML = `<div class="alert alert-danger p-2">${error.message}</div>`;
        }
    }

    if (categoriaSelect) {
        categoriaSelect.addEventListener('change', function() {
            if (this.value) {
                loadCategoryData(this.value);
            } else {
                barcodeContainer.innerHTML = '<div class="alert alert-info p-2">Seleccione una categoría</div>';
                if (codigoBarrasInput) codigoBarrasInput.value = '';
            }
        });

        if (categoriaSelect.value) {
            loadCategoryData(categoriaSelect.value);
        }
    }

    // Intentar generar el código si ya viene un valor desde el backend
    if (codigoBarrasInput.value && codigoBarrasInput.value.length === 13) {
        generateBarcode(codigoBarrasInput.value);
    }
});
</script>
@endsection

