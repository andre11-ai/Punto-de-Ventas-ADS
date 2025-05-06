<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
protected $fillable = [
    'codigo',
    'producto',
    'precio_compra',
    'precio_venta',
    'id_categoria',
    'id_proveedor',
    'codigo_barras',
    'foto'
];

    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class, 'id_proveedor');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }
}
