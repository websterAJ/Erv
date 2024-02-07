<?php

namespace App\Http\Controllers;

use App\Models\pedidos;
use App\Models\productos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class IntendenciaController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function create()
    {
        $data = array();
        $data= $this->DescribeTabla('productos');
        $data['url'] = '/intendencia/create/producto';
        $data['file'] = true;
        return view('form',$data);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request)
    {
        $SaveData =false;
        $file = $request->file('imagen');
        $filename  = time()."-".$file->getClientOriginalName();
        Storage::disk('local')->put("public/productos/".$filename, File::get($file));
        $productos = new productos();
        $productos->imagen = $filename;
        $productos->nombre = $request->input('nombre');
        $productos->descripcion = $request->input('descripcion');
        $productos->cantidad = $request->input('cantidad');
        $productos->precio = $request->input('precio');
        $productos->activo = $request->input('activo');
        $SaveData=$productos->save();
        if($SaveData){
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
    public function show(Request $request)
    {
        $createURL = "";
        $dta = array();
        $columnas = array();
        if ($request->is('intendencia/producto')) {
            $createURL ='/intendencia/create/producto';
            $dta = productos::select(['*'])->get()->toArray();
        }else if($request->is('intendencia/pedido')){
            $createURL ='/intendencia/create/pedido';
            $dta = pedidos::select(['*'])->get()->toArray();
        }

        if(count($dta)>0){
            $columnas= array_keys($dta[0]);
        }
        return view('list',['data' => $dta,'columnas'=>$columnas,"createURL"=>$createURL]);
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
