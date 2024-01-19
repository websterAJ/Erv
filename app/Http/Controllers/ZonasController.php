<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\zonas;
use Illuminate\Support\Facades\DB;

class ZonasController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function create()
    {
        $data = array();
        $data= $this->DescribeTabla('zonas');
        $data['url'] = '/zonas/registro';
        $data['file'] = false;
        return view('form',$data);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre' => 'required',
            'activo' => 'required'
           ]);
           $zona = new zonas();
           $zona->nombre = $request->input('nombre');
           $zona->activo = $request->input('activo');
           if($zona->save()){
            return response()->json([
                'status'    => 'success',
                'msg'       => "information successfully registered"
            ]);
           }else{
            return response()->json([
                'status'    => 'error',
                'msg'       => "information could not be successfully registered"
            ]);
           }
    }

    /**
     * Display the resource.
     */
    public function show()
    {
        $dta = zonas::select("id","nombre","activo")
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
        return view('list',['data' => $dtaFinal,'columnas'=>$columnas,"createURL"=>'/zonas/registro']);
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
