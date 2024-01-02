<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\tipo_personas;
class TipoPersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dtaTipo_Persona = new tipo_personas();
        $dtaTipo_Persona->nombre = 'Sistema';
        $dtaTipo_Persona->activo = true;
        $dtaTipo_Persona->save();

        $dtaTipo_Persona = new tipo_personas();
        $dtaTipo_Persona->nombre = 'Usuario';
        $dtaTipo_Persona->activo = true;
        $dtaTipo_Persona->save();

    }
}
