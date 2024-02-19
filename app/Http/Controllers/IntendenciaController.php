<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use App\Models\pedidos;
use App\Models\productos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class IntendenciaController extends Controller
{
    public function index(Request $request){
        $dta = array();
        $result = (object) array();
        $status =200;
        if($request->is('api/categorias')){
            $dta = categorias::select(['*'])->where('activo',"=","1")->get()->toArray();
        }else if($request->is('api/productos')){
            $sql = productos::select(['id','nombre', 'descripcion', 'stock', 'precio', 'imagen'])->where('activo',"=","1")->get()->toArray();
            $dataConvert = array();
            foreach ($sql as $key=>$value) {
                $aux                = (object)array();
                $aux->id            = $value["id"];
                $aux->nombre        = $value["nombre"];
                $aux->descripcion   = $value["descripcion"];
                $aux->stock         = $value["stock"];
                $aux->precio        = $value["precio"];
                $aux->imagen        = url('storage/productos/').'/'.$value["imagen"];
                array_push($dataConvert,$aux);
            }
            $dta = $dataConvert;
        }
        if(count($dta)<=0){
            $status=400;
            $result->error = true;
            $result->message = "Data no Disponible";
        }else{
            $result->error = false;
            $result->message = "Operacion realizada con exito";
            $result->data = $dta;
        }

        return response()->json($result, $status);
    }
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
        $productos              = new productos();
        $productos->imagen      = $filename;
        $productos->nombre      = $request->input('nombre');
        $productos->descripcion = $request->input('descripcion');
        $productos->stock       = $request->input('stock');
        $productos->precio      = $request->input('precio');
        $productos->activo      = $request->input('activo');
        $SaveData               = $productos->save();
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
            $dta = productos::select(['id','nombre', 'descripcion', 'stock', 'precio', 'imagen'])->get()->toArray();
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
