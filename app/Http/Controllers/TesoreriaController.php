<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\reportePago;
use App\Models\cuotaszonas;
use App\Models\estatus;
use App\Models\zonas;
use App\Models\User;
use App\Mail\ReportePagoMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;


class TesoreriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function createCuota()
    {
        $data = array();
        $data= $this->DescribeTabla('cuotaszonas');
        $data['url'] = '/tesoreria/cuota/registro';
        $data['file'] = false;

        return view('form',$data);
    }

    public function storeCuota(Request $request)
    {
       $this->validate($request,[
        'fecha' => 'required',
        'zona_id' => 'required',
        'monto' => 'required',
       ]);

       $abonos = new cuotaszonas();
       $abonos->fecha = $request->input('fecha');
       $abonos->zona_id = $request->input('zona_id');
       $abonos->monto = $request->input('monto');
       $abonos->estatus_id = 5;
       if($abonos->save()){
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

    public function create()
    {
        $data = array();
        $data= $this->DescribeTabla('reporte_pagos');
        $data['url'] = '/tesoreria/registro';
        $data['file'] = true;
        return view('form',$data);
    }


    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request){
       $this->validate($request,[
        'referencia' => 'required',
        'fecha' => 'required',
        'cuota_id' => 'required',
        'monto' => 'required',
        'comprobante' => 'required'
       ]);
       // Guardar el comprobante en la carpeta correspondiente
       $file = $request->file('comprobante');
       $filename  = time()."-".$file->getClientOriginalName();
       Storage::disk('local')->put("public/".trim($filename," \n\r\t\v\0"), File::get($file));
       // Guardar los datos del formulario en la BD sin el campo de imagen
       $abonos = new reportePago();
       $abonos->referencia = $request->input('referencia');
       $abonos->fecha = $request->input('fecha');
       $abonos->cuota_id = $request->input('cuota_id');
       $abonos->monto = $request->input('monto');
       $abonos->estatus_id = 3;
       $abonos->comprobante = $filename;
       if($abonos->save()){
        // busqueda de correo del usuario
        $user = User::select("personas.correo")
            ->join('personas', 'users.persona_id', '=', 'personas.id')
            ->where('users.id','=',Auth::id())
            ->first();
        // cambio de estatus de la cuota
        $updcuota =cuotaszonas::select("*")
        ->where('id','=',$request->input('cuota_id'))
        ->first();
        $updcuota->estatus_id = 3;
        $updcuota->update();

        Mail::to($user->correo)->send(new ReportePagoMail());
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

    public function  showPagos(){
        $dta = reportePago::select("reporte_pagos.id","zonas.nombre as zona","estatus.nombre as estatus","reporte_pagos.monto")
        ->join('cuotaszonas', 'reporte_pagos.cuota_id', '=', 'cuotaszonas.id')
        ->join('zonas', 'cuotaszonas.zona_id', '=', 'zonas.id')
        ->join('estatus', 'reporte_pagos.estatus_id', '=', 'estatus.id')
        ->get()
        ->toArray();
        $columnas=array();
        if(count($dta)>0){
            $columnas= array_keys($dta[0]);
        }
        return view('list',['data' => $dta,'columnas'=>$columnas,"createURL"=>'/tesoreria/registro']);
    }
    public function show()
    {
        $dta = cuotaszonas::select("cuotaszonas.id","zonas.nombre as zona","estatus.nombre as estatus","cuotaszonas.monto")
        ->join('zonas', 'cuotaszonas.zona_id', '=', 'zonas.id')
        ->join('estatus', 'cuotaszonas.estatus_id', '=', 'estatus.id')
        ->get()
        ->toArray();
        $columnas=array();
        if(count($dta)>0){
            $columnas= array_keys($dta[0]);
        }
        return view('list',['data' => $dta,'columnas'=>$columnas,"createURL"=>'/tesoreria/cuota']);
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
