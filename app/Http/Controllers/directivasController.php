<?php

namespace App\Http\Controllers;

use App\Models\directivas;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data= array();
        $view='form';
        $data=$this->DescribeTabla('directivas');
        return view($view,$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(directivas $directivas)
    {
        //
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
