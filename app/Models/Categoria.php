<?php

namespace App\Models;
use App\Models\Proveedor;
use HasFactory;


use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{

  static $rules = [
    'nombre' => 'required'
  ];

  protected $table = 'categorias';

  protected $fillable = [
    'nombre',
    'upc',
    'proveedor_id',
];


  public function productos()
  {
    return $this->hasMany(Producto::class);
  }

public function proveedor()
{
    return $this->belongsTo(Proveedores::class, 'proveedor_id');
}
public function getFullNameAttribute()
{
    return $this->nombre;
}


}

