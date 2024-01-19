<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\banner;
use App\Models\post;
use App\Models\evento;
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
                $data['url'] = "/create/$tabla";
                $data['file'] = false;
                break;
            case 'blog':
                $data=$this->DescribeTabla('posts');
                $data['url'] = "/create/$tabla";
                $data['file'] = false;
                break;
            case 'banner':
                break;
        }


        return view($view,$data);
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
        if ($request->is('web/banner')) {
            $createURL ='/web/create/banner';
            $dta = banner::select(['*'])->get()->toArray();
        }else if($request->is('web/blog')){
            $createURL ='/web/create/blog';
            $dta = post::select(['*'])->get()->toArray();
        }else if($request->is('web/evento')){
            $createURL ='/web/create/evento';
            //$dta = evento::select(['*'])->get()->toArray();
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
