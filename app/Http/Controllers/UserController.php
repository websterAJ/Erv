<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\personas;
use App\Models\estatus;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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
    public function show()
    {

        $users = User::select("users.id","users.nick","personas.nombre","personas.apellido","personas.correo",
        "estatus.nombre as estatus")
            ->join('personas', 'users.persona_id', '=', 'personas.id')
            ->join('estatus', 'users.estatus_id', '=', 'estatus.id')
            ->get()
            ->toArray();
        return view('list',['data' => $users,'columnas'=>array_keys($users[0])]);
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
