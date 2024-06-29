<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\estatus;
use App\Models\ascensos;
use App\Models\personas;
use Illuminate\Http\Request;
use App\Models\tipo_personas;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class apicontroller extends Controller
{
    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'nick' =>'required|string|max:255',
            'password' =>'required|string|max:255',
        ]);

        $credentials = request(['nick', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('AccessToken');
        $token = $tokenResult->accessToken->token;
        if ($request->input('remember_me') == true){
            $date = Carbon::now()->addDays(7);
            $dateString = $date->format('Y-m-d');
            $tokenResult->accessToken->expires_at = $dateString;
            $tokenResult->save();
        }

        return response()->json([
            'token' => 'Bearer '.$token,
            'expires_at' => Carbon::parse($tokenResult->accessToken->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function LoginApi(Request $request){
        $result = (Object)array();
        $status = 200;
        $result->error = false;
        $this->validate($request,[
            'nick' =>'required|string|max:255',
            'password' =>'required|string|max:255',
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
            'correo' =>'required|string|email|max:255',
            'telefono' =>'required|string|max:255',
            'nick' =>'required|string|max:255',
            'password' =>'required|string|max:255',
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

    public function profileApi(){
        $result = (Object)array();
        $status = 200;
        $result->error = false;
        $result->data = User::select("*")
        ->join('personas', 'users.persona_id', '=', 'personas.id')
        ->join('estatus', 'users.estatus_id', '=', 'estatus.id')
        ->where('users.id','=',Auth::id())
        ->get()
        ->toArray();
        return response()->json($result, $status);
    }
}
