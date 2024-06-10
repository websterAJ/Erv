<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\User;
use App\Models\personas;
use App\Models\estatus;
use Illuminate\Support\Facades\Auth;
use App\Models\tipo_personas;
use App\Models\ascensos;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the form for creating the resource.
     */
    public function create()
    {
        $data = array();
        $data= $this->DescribeTabla('users');
        $data['persona']= $this->DescribeTabla('personas');
        $data['url'] = '/admin/usuarios/registro';
        $data['file'] = false;
        return view('form_user',$data);
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
    public function perfil()
    {

        $users = User::select("*")
            ->join('personas', 'users.persona_id', '=', 'personas.id')
            ->join('estatus', 'users.estatus_id', '=', 'estatus.id')
            ->where('users.id','=',Auth::id())
            ->get()
            ->toArray();
        return view('perfil',['data' => $users,'columnas'=>array_keys($users[0]),"createURL"=>'/admin/usuarios/registro']);
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
        return view('list',['data' => $users,'columnas'=>array_keys($users[0]),"createURL"=>'/admin/usuarios/registro']);
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit($id,Request $request)
    {
        $data = array();
        $data= $this->DescribeTabla('users');
        $data['persona']= $this->DescribeTabla('personas');
        $data['url'] = '/admin/usuarios/update';
        $data['data']= User::select("*")
            ->join('personas', 'users.persona_id', '=', 'personas.id')
            ->join('estatus', 'users.estatus_id', '=', 'estatus.id')
            ->where('users.id','=',Auth::id())
            ->get()
            ->toArray();
        return view('form_user_edit',$data);
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request, $id)
    {
        $result =(Object)array();
        $status = 200;
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Buscar el usuario a actualizar
        $user = User::findOrFail($id);

        // Actualizar los datos del usuario
        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);
        }
        if($user->save()){
            $result->error = false;
            $result->message = "Operacion realizada con exito";
        }else{
            $status = 500;
            $result->error = true;
            $result->message = "Error al eliminar el usuario";
        }

        // Redirigir o devolver una respuesta
        return response()->json($result, $status);
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy($id)
    {
        $result =(Object)array();
        $status = 200;
        // Buscar el usuario a eliminar
        $user = User::findOrFail($id);
        $user->estatus_id =2;
        if($user->save()){
            $result->error = false;
            $result->message = "Operacion realizada con exito";
        }else{
            $status = 500;
            $result->error = true;
            $result->message = "Error al eliminar el usuario";
        }
        return response()->json($result, $status);
    }

    public function LoginApi(Request $request){
        $result = (Object)array();
        $status = 200;
        $result->error = false;

        $this->validate($request,[
            'nick' =>'required|string|email|max:255|unique:users',
            'password' =>'required|string|email|max:255|unique:users',
        ]);

        if (Auth::attempt(['nick' => $request->input('nick'), 'password' => $request->input('password')])) {
            $result->error = false;
            $result->message = "Operacion realizada con exito";
            $result->data = Auth::user();
        }else{
            $status = 500;
            $result->error = true;
            $result->message = "Usuario o contraseña incorrectos";
        }
        return response()->json($result, $status);
    }
    public function RegisterApi(Request $request){
        $result = (Object)array();
        $status = 200;
        $result->error = false;

        $this->validate($request,[
            'nombre' =>'required|string|max:255',
            'apellido' =>'required|string|max:255',
            'correo' =>'required|string|email|max:255|unique:users',
            'telefono' =>'required|string|email|max:255|unique:users',
            'nick' =>'required|string|email|max:255|unique:users',
            'password' =>'required|string|email|max:255|unique:users',
        ]);
        $personasdta= new personas();
        $personasdta->nombre                        =$request->input('nombre');
        $personasdta->apellido                      =$request->input('apellido');
        $personasdta->fecha_nacimiento              =now();
        $personasdta->genero                        ='';
        $personasdta->cedula                        ='';
        $personasdta->correo                        =$request->input('correo');
        $personasdta->telefono                      ='';
        $personasdta->direccion                     ='';
        $personasdta->tipo_personas_id              =tipo_personas::where('nombre','Usuario')->value('id');
        $personasdta->estatus_id                    =estatus::where('nombre','Activo')->value('id');
        $personasdta->ascensos_id                   =ascensos::where('nombre','N/A')->value('id');

        if($personasdta->save()){
            $usuariodta             = new User();
            $usuariodta->nick       = $request->input('nick');
            $usuariodta->password   = bcrypt($request->input('password'));
            $usuariodta->persona_id = $personasdta->id;
            $usuariodta->estatus_id = estatus::where('nombre','Activo')->value('id');
            if($usuariodta->save()){
                $usuariodta->assignRole('user');
                $result->message = "Operacion realizada con exito";
            }else{
                $status = 500;
                $result->error = true;
                $result->message = "Error al registrar el usuario";
            }

        }else{
            $status = 500;
            $result->error = true;
            $result->message = "Error al registrar el usuario";
        }
        return response()->json($result, $status);
    }
}
