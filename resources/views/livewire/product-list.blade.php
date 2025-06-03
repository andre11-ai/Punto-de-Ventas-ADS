
<div class="row">
    <!-- Lista de productos -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <input wire:model.debounce.300ms="search" type="text"
                       placeholder="Buscar por nombre o código de barras..."
                       class="form-control mb-3">

                @if($products->isEmpty())
                    <div class="alert alert-warning">
                        No se encontraron productos con ese criterio de búsqueda.
                    </div>
                @else
                    <div class="row">
                        @foreach ($products as $product)
                            <div class="col-md-3 mb-4">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        @if($product->foto)
                                            <img src="{{ asset('storage/'.$product->foto) }}"
                                                 class="img-fluid mb-2"
                                                 style="max-height: 100px;">
                                        @else
                                            <img src="{{ asset('img/default.png') }}"
                                                 class="img-fluid mb-2"
                                                 style="max-height: 100px;">
                                        @endif

                                        <button wire:click="addToCart({{ $product->id }})"
                                                class="btn btn-primary btn-sm mb-2">
                                            <i class="fas fa-cart-plus"></i> Agregar
                                        </button>

                                        <h6 class="card-title">{{ $product->producto }}</h6>
                                        <p class="mb-1"><small>Código: {{ $product->codigo_barras }}</small></p>
                                        <p class="mb-1"><strong>${{ number_format($product->precio_venta, 2) }}</strong></p>

                                        @if($product->promocion)
                                            <span class="badge bg-warning text-dark">
                                                {{ $product->promocion->tipo }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Carrito de compras -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Carrito de Compras</h5>
            </div>
            <div class="card-body">
                @if(count($cartItems) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr wire:key="cart-item-{{ $item['rowId'] }}">
                                    <td>
                                        {{ $item['name'] }}<br>
                                        <small>{{ $item['options']['codigo_barras'] ?? '' }}</small>
                                    </td>
                                    <td>${{ number_format($item['price'], 2) }}</td>
                                    <td>
                                        <input type="number"
                                               wire:model="quantity.{{ $item['rowId'] }}"
                                               wire:change.debounce.500ms="updateQuantity('{{ $item['rowId'] }}')"
                                               min="1"
                                               class="form-control form-control-sm">
                                    </td>
                                    <td>${{ number_format($item['price'] * $item['qty'], 2) }}</td>
                                    <td>
                                        <button wire:click="removeFromCart('{{ $item['rowId'] }}')"
                                                class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <h4>Total: ${{ number_format(collect($cartItems)->sum(fn($i) => $i['price'] * $i['qty']), 2) }}</h4>
                    </div>
                @else
                    <div class="alert alert-info" wire:key="empty-cart">
                        El carrito está vacío
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('notify', (event) => {
            Swal.fire({
                title: event.message,
                icon: event.type,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });

        Livewire.on('cartUpdated', () => {
            console.log('Carrito actualizado');
        });
    });
</script>
@endpush
