<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class personas extends Model
{
    use HasFactory;
    protected $table = 'personas';

    public function tipo_personas(): HasOne{
        return $this->hasOne(tipo_personas::class);
    }
    public function ascensos(): HasOne{
        return $this->hasOne(ascensos::class);
    }
    public function estatus(): HasOne{
        return $this->hasOne(estatus::class);
    }
}
