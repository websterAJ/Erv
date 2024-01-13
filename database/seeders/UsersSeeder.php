<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\personas;
use App\Models\tipo_personas;
use App\Models\estatus;
use App\Models\ascensos;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estatusId= estatus::where('nombre','Activo')->value('id');

        $personasdta= new personas();
        $personasdta->nombre                        ='Administrador';
        $personasdta->apellido                      ='Admin';
        $personasdta->fecha_nacimiento              =now();
        $personasdta->genero                        ='Masculino';
        $personasdta->cedula                        ='12345678';
        $personasdta->correo                        ='alexander20012@hotmail.com';
        $personasdta->telefono                      ='+584123082432';
        $personasdta->direccion                     ='caracas';
        $personasdta->tipo_personas_id               =tipo_personas::where('nombre','Sistema')->value('id');
        $personasdta->estatus_id                    =$estatusId;
        $personasdta->ascensos_id                   =ascensos::where('nombre','N/A')->value('id');

        if($personasdta->save()){
            $usuariodta             = new User();
            $usuariodta->nick       = 'Admin';
            $usuariodta->password   = bcrypt("23124156Aj.");
            $usuariodta->persona_id = $personasdta->id;
            $usuariodta->estatus_id = $estatusId;
            $usuariodta->save();
            $usuariodta->assignRole('administrator');
        }
    }
}
