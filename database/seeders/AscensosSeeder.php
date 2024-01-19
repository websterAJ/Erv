<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ascensos;
use App\Models\tipo_ascensos;

class AscensosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dtatipo_ascensos= new tipo_ascensos();
        $dtatipo_ascensos->nombre='N/A';
        $dtatipo_ascensos->activo=true;
        if($dtatipo_ascensos->save()){
            $dtaAscensos = new ascensos();
            $dtaAscensos->nombre = 'N/A';
            $dtaAscensos->tipo_id =$dtatipo_ascensos->id;
            $dtaAscensos->activo = true;
            $dtaAscensos->save();
        }

    }
}
