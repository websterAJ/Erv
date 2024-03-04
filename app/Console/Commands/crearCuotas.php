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

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Accept: application/json"
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response, true);


        //$baseUrl = env('API_ENDPOINT');
        //$client=new Client(['base_uri' => $baseUrl]);
        //$response = $client->request('GET', "");
        //$data = json_decode($response->getBody());

        $zonas = zonas::select(['id'])->get();
        $config = config::select(['*'])->first();
        foreach ($zonas as $key => $value) {
            $cuotas = new cuotaszonas();
            $cuotas->zona_id=$value['id'];
            $cuotas->fecha=date('Y-m-d');
            $cuotas->monto=($data['price'] * $config->monto_cuota) * $zonas->cant_oficiales;
            $cuotas->save();
        }
    }
}
