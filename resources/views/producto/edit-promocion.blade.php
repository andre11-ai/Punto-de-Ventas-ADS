<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Editar Promoción</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
<form method="POST" action="{{ route('productos.update-promocion', $producto->id) }}" id="formEditarPromocion">
    @csrf
    @method('PUT')
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Información de la Promoción</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Producto Actual:</label>
                                    <input type="text" class="form-control" value="{{ $producto->producto }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Aplicada por:</label>
                                    <input type="text" class="form-control"
                                        value="@if($producto->promocion->id_categoria)
                                            Categoría: {{ $producto->categoria->nombre }}
                                        @elseif($producto->promocion->id_proveedor)
                                            Proveedor: {{ $producto->proveedor->nombre }}
                                        @else
                                            Producto individual
                                        @endif" readonly>
                                </div>

                                <div class="col-md-4">
    <label for="tipo_promocion">Tipo de Promoción</label>
    <select name="tipo_promocion" class="form-select" required>
        <option value="ninguna" selected disabled>-- Seleccione --</option>
        <option value="ninguna">Ninguna</option>
        <option value="2x1">2x1</option>
        <option value="3x2">3x2</option>
        <option value="50%">50% de descuento</option>
        <option value="Precio especial">Precio especial</option>
        <option value="Segunda unidad al 30%">Segunda unidad al 30%</option>
    </select>
    <div class="invalid-feedback">Por favor selecciona un tipo de promoción</div>
</div>
                            </div>
                        </div>
                    </div>

                <div class="col-md-6">
    <div class="card">
        <div class="card-header bg-light">
            <h6 class="mb-0">Productos con esta promoción</h6>
        </div>
        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
            @if($productosRelacionados && $productosRelacionados->count() > 0)
                <div class="list-group">
 @foreach($productosRelacionados as $prod)
    <div class="list-group-item">
        <div class="form-check">
<input class="form-check-input"
       type="checkbox"
       name="productos[]"
       value="{{ $prod->id }}"
       id="prod_{{ $prod->id }}"
       {{ in_array($prod->id, $productosConEstaPromocion ?? []) ? 'checked' : '' }}>

            <label class="form-check-label" for="prod_{{ $prod->id }}">
                {{ $prod->producto }}
                <span class="badge bg-secondary">
                    {{ $prod->categoria->nombre ?? 'Sin categoría' }}
                </span>
            </label>
        </div>
        <div class="mt-2">
            <small class="text-muted">
                Promoción actual:
                <span class="badge bg-{{ $prod->promocion ? 'warning' : 'light' }} text-dark">
                    {{ $prod->promocion ? $prod->promocion->tipo : 'Ninguna' }}
                </span>
            </small>
        </div>
    </div>
@endforeach

                </div>
            @else
                <div class="alert alert-info mb-0">
                    No hay otros productos relacionados con esta promoción.
                </div>
            @endif
        </div>
    </div>
</div>
                </div>
            </div>
   <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </div>
        </form>
    </div>
</div>
