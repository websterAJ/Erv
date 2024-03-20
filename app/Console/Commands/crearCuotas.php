<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\zonas;
use App\Models\config;
use App\Models\cuotaszonas;
use GuzzleHttp\Client;


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
        $url = env('API_ENDPOINT');
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Accept: application/json"
        ));// Ejecutando la solicitud cURL
        $response = curl_exec($curl);
        
        // Verificando si ocurrió algún error durante la solicitud
        if ($e = curl_error($curl)) {
            echo $e;
        } else {
            $decodedData = json_decode($response, true);
        
            $quote = $decodedData['sources']['BCV']['quote'];
           
            $zonas = zonas::select(['id'])->get();
            $config = config::select(['*'])->first();
            foreach ($zonas as $key => $value) {
                $cuotas = new cuotaszonas();
                $cuotas->zona_id=$value['id'];
                $cuotas->fecha=date('Y-m-d');
                $cuotas->monto=((float)$quote * $config->monto_cuota) * $zonas->cant_oficiales;
                $cuotas->save();
            }
        }

        curl_close($curl);
    }
}
