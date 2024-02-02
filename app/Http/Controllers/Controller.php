<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\estatus;
use App\Models\zonas;
use App\Models\ascensos;
use App\Models\tipo_personas;
use App\Models\cuotaszonas;
use App\Models\categorias;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function DescribeTabla($tabla)
    {
        $data = array();
        $table = DB::select("SHOW COLUMNS FROM ". $tabla);
        $data['campos'] = $table;
        foreach ($table as $object) {
            foreach ($object as $key =>$value) {
                if($key == 'Field'){
                    switch($value){
                        case 'tipo_personas_id':
                            $tipoPersona = tipo_personas::all(['nombre','id'])->toArray();
                            $data['tipoPersona']=$tipoPersona;
                            break;
                        case 'ascensos_id':
                            $ascensos = ascensos::all(['nombre','id'])->toArray();
                            $data['ascensos']=$ascensos;
                            break;
                        case 'estatus_id':
                            $status = estatus::all(['nombre','id'])->toArray();
                            $data['status']=$status;
                            break;
                        case 'zona_id':
                            $zonas = zonas::all(['nombre','id'])->toArray();
                        $data['zonas']=$zonas;
                            break;
                        case 'cuota_id':
                            $cuotas = cuotaszonas::join('zonas as z','z.id','=','cuotaszonas.zona_id')
                            ->select('cuotaszonas.id','z.nombre','cuotaszonas.fecha','cuotaszonas.monto')
                            ->where('cuotaszonas.estatus_id',"=","5")
                            ->get()
                            ->toArray();
                            $data['cuota'] = $cuotas;
                            break;
                        case 'categoria_id':
                            $categorias = categorias::select('*')
                            ->get()
                            ->toArray();
                            $data['categorias'] = $categorias;
                            break;
                    }
                }
            }
        }
        return $data;
    }
}
