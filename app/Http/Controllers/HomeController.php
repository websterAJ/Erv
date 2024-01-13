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
            $table = DB::table('information_schema.columns')
                ->select(['column_name','data_type'])
                ->where("table_name",'=','configs')
                ->get()
                ->toArray();
            $data['campos'] = $table;
            $data['url'] = '/admin/config';
            $data['file'] = false;

            foreach ($table as $object) {
                if ($object->column_name === 'estatus_id') {
                    $status = estatus::all(['nombre','id'])->toArray();
                    $data['status']=$status;
                }
                if ($object->column_name === 'zona_id') {
                    $zonas = zonas::all(['nombre','id'])->toArray();
                    $data['zonas']=$zonas;
                }
            }
            $view='form';
        }else{
            $view="home";
        }
        return view($view,$data);
    }
}
