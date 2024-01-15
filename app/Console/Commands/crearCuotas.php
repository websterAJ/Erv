<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\zonas;
use App\Models\cuotaszonas;


class crearCuotas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crear-cuotas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creacion de registros de cobro de cuotas';

    /**
     * Execute the console command.
     */
    public function handle(){
        $zonas = zonas::all(['id'])->get();
        foreach ($zonas as $key => $value) {
            $cuotas = new cuotaszonas();
            $cuotas->zona_id=$value['id'];
            $cuotas->fecha=date('Y-m-d');
            $cuotas->monto=date('Y-m-d');
        }
    }
}
