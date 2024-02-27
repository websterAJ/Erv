<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\banner;
use App\Models\post;
use App\Models\eventos;
use App\Models\galeria;
use App\Models\contactanos;

class WebController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function create($tabla)
    {
        $data= array();
        $view='form';
        switch($tabla){
            case 'banner':
                $data=$this->DescribeTabla($tabla.'s');
                $data['url'] = "/web/create/$tabla";
                $data['file'] = true;
                break;
            case 'blog':
                $data=$this->DescribeTabla('posts');
                $data['url'] = "/web/create/$tabla";
                $data['file'] = true;
                break;
            case 'evento':
                $data=$this->DescribeTabla('eventos');
                $data['url'] = "/web/create/$tabla";
                $data['file'] = true;
                break;
        }


        return view($view,$data);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store($tabla,Request $request)
    {
        $SaveData =false;
        switch($tabla){
            case 'banner':
                $file = $request->file('imagen');
                $filename  = time()."-".$file->getClientOriginalName();
                Storage::disk('local')->put("public/banner/".trim($filename," \n\r\t\v\0"), File::get($file));
                $banner = new banner();
                $banner->imagen = $filename;
                $banner->activo = $request->input('activo');
                $SaveData=$banner->save();
                break;
            case 'blog':
                $file = $request->file('imagen');
                $filename  = time()."-".$file->getClientOriginalName();
                Storage::disk('local')->put("public/blog/".trim($filename," \n\r\t\v\0"), File::get($file));
                $post = new post();
                $post->imagen = $filename;
                $post->titulo = $request->input('titulo');;
                $post->resumen = $request->input('resumen');;
                $post->contenido = $request->input('contenido');
                $post->categoria_id = $request->input('categoria_id');
                $post->activo = $request->input('activo');
                $SaveData=$post->save();
                break;
                case 'evento':
                    $file = $request->file('flayer');
                    $filename  = time()."-".$file->getClientOriginalName();
                    Storage::disk('local')->put("public/flayer/".trim($filename," \n\r\t\v\0"), File::get($file));
                    $evento = new eventos();
                    $evento->flayer = $filename;
                    $evento->titulo = $request->input('titulo');;
                    $evento->resumen = $request->input('resumen');;
                    $evento->costo = $request->input('costo');
                    $evento->fecha = $request->input('fecha');
                    $SaveData=$evento->save();
                    break;
        }
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
        if ($request->is('web/banner')) {
            $createURL ='/web/create/banner';
            $dta = banner::select(['id','imagen','activo'])->get()->toArray();
        }else if($request->is('web/blog')){
            $createURL ='/web/create/blog';
            $dta = post::select(['*'])->get()->toArray();
        }else if($request->is('web/evento')){
            $createURL ='/web/create/evento';
            $dta = eventos::select(['id','titulo','flayer','resumen','costo','fecha'])->get()->toArray();
        }else if($request->is('web/contactanos')){
            $createURL ='#';
            //$dta = contactanos::select(['*'])->get()->toArray();
        }else if($request->is('web/galeria')){
            $createURL ='/web/create/galeria';
            $dta = galeria::select(['*'])->get()->toArray();
        }

        if(count($dta)>0){
            $columnas= array_keys($dta[0]);
        }
        return view('list',['data' => $dta,'columnas'=>$columnas,"createURL"=>$createURL]);
    }

    public function index(Request $request){
        $dta = array();
        $result = (object) array();
        $status =200;
        if ($request->is('api/banner')) {
            $sql = banner::select(['id','imagen'])->where('activo',"=","1")->get()->toArray();
            $dataConvert = array();
            foreach ($sql as $key=>$value) {
                $aux = (object)array();
                $aux->id =$value["id"];
                $aux->imagen = url('storage/banner/').'/'.$value["imagen"];
                array_push($dataConvert,$aux);
            }
            $dta = $dataConvert;
        }else if($request->is('api/blog')){
            $dta = post::select(['*'])->get()->toArray();
        }else if($request->is('api/eventos')){
            $sql = eventos::select(['*'])->get()->toArray();
            $dataConvert = array();
            foreach ($sql as $key=>$value) {
                $aux = (object)array();
                $aux->id	    = $value["id"];
                $aux->titulo    = $value["titulo"];
                $aux->flayer    = url('storage/flayer/').'/'.$value["flayer"];;
                $aux->resumen   = $value["resumen"];
                $aux->costo     = $value["costo"];
                $aux->fecha     = $value["fecha"];
                array_push($dataConvert,$aux);
            }
            $dta = $dataConvert;
        }else if($request->is('api/contactanos')){
        }else if($request->is('api/galeria')){
            $dta = galeria::select(['*'])->get()->toArray();
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
