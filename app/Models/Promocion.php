<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones';

    protected $fillable = ['tipo', 'id_categoria', 'id_proveedor'];

    public function categoria() {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function proveedor() {
        return $this->belongsTo(Proveedores::class, 'id_proveedor');
    }

    public function productos() {
        return $this->hasMany(Producto::class, 'promocion_id');
    }

}
