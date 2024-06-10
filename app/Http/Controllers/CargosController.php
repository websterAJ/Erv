<?php

namespace App\Http\Controllers;

use App\Models\Cargos;
use Illuminate\Http\Request;

class CargosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $createURL = "";
        $dta = array();
        $columnas = array();
        $createURL ='/cargos/create';
        $dta = Cargos::select(['*'])->get()->toArray();
        if(count($dta)>0){
            $columnas= array_keys($dta[0]);
        }
        return view('list',['data' => $dta,'columnas'=>$columnas,"createURL"=>$createURL]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data= array();
        $view='form';
        $data=$this->DescribeTabla("cargos");
        $data['url'] = "/cargos/create/";
        return view($view,$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Cargos = new Cargos();
        $Cargos->nombre = $request->input('nombre');
        $Cargos->activo = $request->input('activo');
        if($Cargos->save()){
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
     * Display the specified resource.
     */
    public function show($id)
    {
        $data= array();
        $view='form';
        $data=$this->DescribeTabla("cargos");
        $data['url'] = "/web/update/cargos/".$id;
        $data['data'] = Cargos::find($id);
        return view($view,$data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data= array();
        $view='form';
        $data=$this->DescribeTabla("cargos");
        $data['url'] = "/web/update/cargos/".$id;
        $data['data'] = Cargos::find($id);
        return view($view,$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $Cargos = Cargos::find($id);
        $Cargos->nombre = $request->input('nombre');
        $Cargos->activo = $request->input('activo');
        if($Cargos->save()){
            return response()->json([
               'status'    =>'success',
               'msg'       => "information successfully updated"
            ]);
        }else{
            return response()->json([
               'status'    => 'error',
               'msg'       => "information could not be successfully updated"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Cargos = Cargos::find($id);
        if($Cargos->destroy()){
            return response()->json([
               'status'    =>'success',
               'msg'       => "information successfully deleted"
            ]);
        }else{
            return response()->json([
               'status'    => 'error',
               'msg'       => "information could not be successfully deleted"
            ]);
        }
    }
}
