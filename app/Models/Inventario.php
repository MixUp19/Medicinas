<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario'; 
    protected $primaryKey = ['id_sucursal', 'id_medicamento']; 
    public $incrementing = false; 
    protected $keyType = 'string';
    protected $fillable = ['id_sucursal', 'id_medicamento', 'existencias', 'stock_minimo', 'stock_maximo'];
    public $timestamps = false; 

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento', 'id_medicamento');
    }
}