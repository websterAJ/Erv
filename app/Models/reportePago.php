<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class reportePago extends Model
{
    protected $table = 'reporte_pagos';
    public function cuotaszonas(): HasOne{
        return $this->hasOne(cuotaszonas::class);
    }
    public function estatus(): HasOne{
        return $this->hasOne(estatus::class);
    }
    use HasFactory;

    protected $fillable = [
        'referencia',
        'fecha',
        'cuota_id',
        'monto',
        'estatus_id'
    ];

}
