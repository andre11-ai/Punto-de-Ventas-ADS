<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Valida productos y cantidades en el carrito
            'productos'       => 'required|array|min:1',
            'productos.*.id'  => 'required|exists:products,id',
            'productos.*.qty' => 'required|integer|min:1',

            // Validación de stock (puedes validar en el Controller o con un custom rule)
            // 'productos.*.qty' => ['required', 'integer', 'min:1', new StockDisponible],

            // Si se va a registrar un deudor (venta a crédito/adeudo)
            'deudor_nombre'   => 'required_if:metodo_pago,adeudo|string|max:255',
            'deudor_telefono' => 'required_if:metodo_pago,adeudo|string|max:20',
            'total'           => 'required|numeric|min:0.01',
            'metodo_pago'     => 'required|in:efectivo,tarjeta,adeudo',
        ];
    }

    public function messages()
    {
        return [
            'productos.required'       => 'Debes agregar al menos un producto al carrito.',
            'productos.*.id.required'  => 'Falta el identificador de un producto.',
            'productos.*.id.exists'    => 'Uno de los productos seleccionados no existe.',
            'productos.*.qty.required' => 'Falta la cantidad de un producto.',
            'productos.*.qty.integer'  => 'La cantidad debe ser un número entero.',
            'productos.*.qty.min'      => 'La cantidad mínima para cada producto es 1.',
            'deudor_nombre.required_if'   => 'El nombre del deudor es obligatorio si el método es adeudo.',
            'deudor_telefono.required_if' => 'El teléfono del deudor es obligatorio si el método es adeudo.',
            'total.required'           => 'El total de la venta es obligatorio.',
            'total.numeric'            => 'El total debe ser un número.',
            'total.min'                => 'El total debe ser mayor a cero.',
            'metodo_pago.required'     => 'Selecciona un método de pago.',
            'metodo_pago.in'           => 'El método de pago seleccionado no es válido.',
        ];
    }
}
