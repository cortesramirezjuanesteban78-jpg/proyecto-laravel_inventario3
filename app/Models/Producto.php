<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model {
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;
    protected $fillable = ['id_categoria','id_proveedor','codigo','nombre','descripcion','precio','stock_actual','stock_minimo','imagen','estado'];

    public function categoria() { return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria'); }
    public function proveedor() { return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor'); }
    public function detalleVentas() { return $this->hasMany(DetalleVenta::class, 'id_producto', 'id_producto'); }
}
