<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\config;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the form for creating the resource.
     */
    public function create()
    {


    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = null;
        $this->validate($request,[
            "monto_cuota"           => 'required',
            "porcentaje_cuota"      => 'required',
            "dia_cobro_cuota"       => 'required',
            "activo"                => 'required',
            "moneda"                => 'required'
        ]);

        $config = new config();
        $config->monto_cuota        = $request->input('monto_cuota');
        $config->porcentaje_cuota   = $request->input('porcentaje_cuota');
        $config->dia_cobro_cuota    = $request->input('dia_cobro_cuota');
        $config->activo             = $request->input('activo');
        $config->moneda             = $request->input('moneda');
        if($config->save()){
            $result = response()->json([
                'status'    => 'success',
                'msg'       => "information successfully registered"
            ]);
        }else{
            $result = response()->json([
                'status'    => 'error',
                'msg'       => "information could not be successfully registered"
            ]);
        }
        return $result;
    }

    /**
     * Display the resource.
     */
    public function show()
    {
        $dta = config::select("monto_cuota","porcentaje_cuota","dia_cobro_cuota","activo","moneda")
        ->get()
        ->toArray();
        $columnas=array();
        $dtaFinal = array();
        foreach ($dta as $key => $value) {
            $aux = array();
            foreach ($value as $j => $ji) {
                if($j == "activo"){
                    if($ji){
                        $aux[$j]= '<i class="fa fa-ws fa-check"></i>';
                    }else{
                        $aux[$j]= '';
                    }
                }else{
                    $aux[$j]= $ji;
                }
            }
            $dtaFinal[$key]=$aux;
        }
        if(count($dta)>0){
            $columnas= array_keys($dta[0]);
        }
        return view('list',['data' => $dtaFinal,'columnas'=>$columnas,"createURL"=>'#']);
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(): never
    {
        abort(404);
    }
}
