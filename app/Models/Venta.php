<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model {
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';
    public $timestamps = false;
    protected $fillable = ['id_usuario','id_cliente','fecha_venta','metodo_pago','estado','total'];

    public function usuario()  { return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario'); }
    public function cliente()  { return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente'); }
    public function detalles() { return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta'); }
}
