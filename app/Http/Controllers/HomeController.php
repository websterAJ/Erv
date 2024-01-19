<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\config;
use Illuminate\Support\Facades\DB;
use App\Models\estatus;
use App\Models\zonas;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $dta = config::select("*")->get()->toArray();
        $view="";
        $data = array();

        if(count($dta) <= 0){
            $data= $this->DescribeTabla('configs');
            $data['url'] = '/admin/config';
            $data['file'] = false;
            $view='form';
        }else{
            $view="home";
        }
        return view($view,$data);
    }
}
