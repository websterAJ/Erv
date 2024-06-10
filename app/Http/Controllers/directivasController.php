<?php

namespace App\Http\Controllers;

use App\Models\directivas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class directivasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $createURL = "";
        $dta = array();
        $columnas = array();
        $createURL ='/directiva/create';
        $dta = directivas::select("*")
            ->join('personas', 'directivas.personas_id', '=', 'personas.id')
            ->join('cargos', 'directivas.cargos_id', '=', 'cargos.id')
            ->get()
            ->toArray();
        if(count($dta)>0){
            $columnas= array_keys($dta[0]);
        }
        return view('list',['data' => $dta,'columnas'=>array_keys($columnas),"createURL"=>$createURL]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data= array();
        $view='form';
        $data=$this->DescribeTabla('directivas');
        $data['file']=true;
        $data['url']='/directiva/create';
        return view($view,$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $directivas = new directivas();
        $file = $request->file('imagen');
        $filename  = time()."-".$file->getClientOriginalName();
        Storage::disk('local')->put("public/directiva/".trim($filename," \n\r\t\v\0"), File::get($file));
        $directivas->imagen = $filename;
        $directivas->personas_id = $request->input('personas_id');
        $directivas->cargos_id = $request->input('cargos_id');
        $directivas->save();
        return redirect('/directiva');
    }

    /**
     * Display the specified resource.
     */
    public function show(directivas $directivas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id,Request $request)
    {
        $data= array();
        $view='form';
        $data=$this->DescribeTabla("directivas");
        $data['file']=true;
        $data['url']='/directiva/edit/'.$id;
        return view($view,$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, directivas $directivas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(directivas $directivas)
    {
        //
    }
}
