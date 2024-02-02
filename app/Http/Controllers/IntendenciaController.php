<?php

namespace App\Http\Controllers;

use App\Models\pedidos;
use App\Models\productos;
use Illuminate\Http\Request;

class IntendenciaController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function create(): never
    {
        abort(404);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request): never
    {
        abort(404);
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
