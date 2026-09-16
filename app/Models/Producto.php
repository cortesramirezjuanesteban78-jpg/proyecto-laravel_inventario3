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

    public function getImagenUrlAttribute()
    {
        if (empty($this->imagen)) {
            return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400&q=70';
        }

        if (str_starts_with($this->imagen, 'http://') || str_starts_with($this->imagen, 'https://')) {
            return $this->imagen;
        }

        return asset($this->imagen);
    }
}
