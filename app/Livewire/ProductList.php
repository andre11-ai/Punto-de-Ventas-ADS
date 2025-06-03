<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;
use Gloudemans\Shoppingcart\Facades\Cart;

class ProductList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $perPage = 12;
    public $quantity = [];
    public $cartItems = [];

    public function mount()
    {
        $this->refreshCart();
    }

    protected function getCartContent()
    {
        return Cart::instance('shopping')->content()->map(function ($item) {
            return [
                'rowId' => $item->rowId,
                'id' => $item->id,
                'name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'options' => $item->options->all()
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.product-list', [
            'products' => Producto::when($this->search, function ($query) {
                $query->where('producto', 'like', '%'.$this->search.'%')
                      ->orWhere('codigo_barras', 'like', '%'.$this->search.'%');
            })->orderBy('producto')->paginate($this->perPage)
        ]);
    }

    public function addToCart($productId)
    {
        $product = Producto::findOrFail($productId);

        $existingItem = Cart::instance('shopping')->search(function ($cartItem) use ($productId) {
            return $cartItem->id == $productId;
        })->first();

        if ($existingItem) {
            Cart::instance('shopping')->update($existingItem->rowId, $existingItem->qty + 1);
        } else {
            Cart::instance('shopping')->add([
                'id' => $product->id,
                'name' => $product->producto,
                'price' => $product->precio_venta,
                'qty' => 1,
                'options' => [
                    'codigo_barras' => $product->codigo_barras,
                    'foto' => $product->foto
                ]
            ]);
        }

        $this->refreshCart();
        $this->dispatch('cartUpdated');
    }

    public function updateQuantity($rowId)
    {
        if (!isset($this->quantity[$rowId]) || $this->quantity[$rowId] < 1) {
            $this->removeFromCart($rowId);
            return;
        }

        Cart::update($rowId, $this->quantity[$rowId]);
        $this->refreshCart();
    }

    public function removeFromCart($rowId)
    {
        Cart::remove($rowId);
        $this->refreshCart();
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Producto eliminado'
        ]);
    }

    #[\Livewire\Attributes\On('cartUpdated')]
    public function refreshCart()
    {
        $this->cartItems = $this->getCartContent();
    }
}
