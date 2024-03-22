<?php

namespace App\Http\Controllers;

use App\Models\carrito;
use App\Models\detalle_carrito;
use App\Models\categorias;
use App\Models\pedidos;
use App\Models\factura;
use App\Models\detalle_pedidos;
use App\Models\detalle_factura;
use App\Models\productos;
use App\Models\payments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class IntendenciaController extends Controller
{
    public function createPayments(Request $request){
        $this->validate($request,[
            'referencia' => 'required',
            'fecha' => 'required',
            'pedido_id' => 'required',
            'monto' => 'required',
            'comprobante' => 'required',
            'pedido_id' => 'required',
        ]);
        $pedido=pedidos::select("id")
        ->where('id','=',$request->input('pedido_id'))
        ->first();
        if($pedido){
            $payments= new payments();

            $file = $request->file('comprobante');
            $filename  = time()."-".$file->getClientOriginalName();
            Storage::disk('local')->put("public/payments/".trim($filename," \n\r\t\v\0"), File::get($file));
            $payments->comprobante = $filename;
            $payments->estatus_id = 3;
            $payments->referencia = $request->input('referencia');
            $payments->fecha = $request->input('fecha');
            $payments->pedido_id = $request->input('pedido_id');
            $payments->monto = $request->input('monto');
            if($payments->save()){
                /*// busqueda de correo del usuario
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

                Mail::to($user->correo)->send(new ReportePagoMail());*/
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
    }
    public function getPedido(Request $request){
        $this->validate($request,[
            'users_id'      => 'required',
        ]);
        $pedido=pedidos::select("*")
        ->where('users_id','=',$request->input('users_id'))
        ->join('detalle_pedidos', 'detalle_pedidos.pedido_id = pedidos.id')
        ->get()
        ->toArray();
        if(count($pedido)>0){
            return response()->json([
                'status'    => 'success',
                'data'      => $pedido,
                'msg'       => "information successfully"
            ]);
        }else{
            return response()->json([
                'status'    => 'error',
                'msg'       => "information could not be successfully"
            ]);
        }
    }
    public function createPedido(Request $request){
        $this->validate($request,[
            'users_id'      => 'required',
            'carrito_id'    => 'required'
        ]);
        $carrito=carrito::select('*')
        ->where('status_id','=','1')
        ->where('users_id','=',$request->input('users_id'))
        ->where('id','=',$request->input('carrito_id'))
        ->first();
        if ($carrito) {
            $pedido = new pedidos();
            $pedido->users_id = $carrito->users_id;
            $pedido->fecha    = $carrito->fecha;
            $pedido->total    = $carrito->total;
            $pedido->iva      = $carrito->iva;
            $pedido->status_id= 3;

            if($pedido->save()){
                $detalle_carrito=detalle_carrito::select('*')
                ->where('carrito_id','=',$request->input('carrito_id'))
                ->get();
                $error=false;
                $idDetalle = array();
                foreach ($detalle_carrito as $key => $value) {
                    $detalle=new detalle_pedidos();
                    $detalle->cantidad=$value->cantidad;
                    $detalle->subtotal=$value->precio;
                    $detalle->producto_id=$value->producto_id;
                    $detalle->pedidos_id=$pedido->id;
                    $saveDetalle = $detalle->save();
                    if(!$saveDetalle){
                        $error=true;
                        return response()->json([
                            'status'    => 'error',
                            'msg'       => "shopping cart not found"
                        ]);
                    }
                    array_push($idDetalle,$value->id);
                }
                if($error==false){
                    detalle_carrito::destroy($idDetalle);
                    carrito::destroy($request->input('carrito_id'));
                    return response()->json([
                        'status'    => 'success',
                        "data"      =>  $carrito,
                        'msg'       => "information successfully registered"
                    ]);
                }
            }

        }else{
            return response()->json([
                'status'    => 'error',
                'msg'       => "shopping cart not found"
            ]);
        }

    }
    public function getFactura(){
        $this->validate($request,[
            'users_id'      => 'required',
        ]);
        $pedido=factura::select("*")
        ->where('users_id','=',$request->input('users_id'))
        ->join('detalle_factura', 'detalle_factura.factura_id = factura.id')
        ->get()
        ->toArray();
        if(count($pedido)>0){
            return response()->json([
                'status'    => 'success',
                'data'      => $pedido,
                'msg'       => "information successfully"
            ]);
        }else{
            return response()->json([
                'status'    => 'error',
                'msg'       => "information could not be successfully"
            ]);
        }

    }
    public function createFactura(){
        $this->validate($request,[
            'users_id'      => 'required',
            'pedido_id'    => 'required'
        ]);
        $carrito=pedidos::select('*')
        ->where('status_id','=','1')
        ->where('users_id','=',$request->input('users_id'))
        ->where('id','=',$request->input('pedido_id'))
        ->first();
        if ($carrito) {
            $pedido = new factura();
            $pedido->users_id = $carrito->users_id;
            $pedido->fecha    = $carrito->fecha;
            $pedido->total    = $carrito->total;
            $pedido->iva      = $carrito->iva;
            $pedido->status_id= 3;

            if($pedido->save()){
                $detalle_carrito=detalle_pedidos::select('*')
                ->where('pedido_id','=',$request->input('pedido_id'))
                ->get();
                $error=false;
                $idDetalle = array();
                foreach ($detalle_carrito as $key => $value) {
                    $detalle=new detalle_factura();
                    $detalle->cantidad=$value->cantidad;
                    $detalle->subtotal=$value->precio;
                    $detalle->producto_id=$value->producto_id;
                    $detalle->pedidos_id=$pedido->id;
                    $saveDetalle = $detalle->save();
                    if(!$saveDetalle){
                        $error=true;
                        return response()->json([
                            'status'    => 'error',
                            'msg'       => "shopping cart not found"
                        ]);
                    }
                    array_push($idDetalle,$value->id);
                }
                if($error==false){
                    return response()->json([
                        'status'    => 'success',
                        "data"      =>  $carrito,
                        'msg'       => "information successfully registered"
                    ]);
                }
            }

        }else{
            return response()->json([
                'status'    => 'error',
                'msg'       => "shopping cart not found"
            ]);
        }
    }
    public function getPago(){

    }
    public function dltProducto(Request $request){
        $this->validate($request,[
            'producto_id'   => 'required',
            'carrito_id'    => 'required',
        ]);
        $carrito=carrito::select('*')
                ->where('status_id','=','1')
                ->where('id','=',$request->input('carrito_id'))
                ->first();
        if ($carrito) {
            $detalle_carrito = new detalle_carrito::select('id')->where('id','=',$request->input('producto_id'))->first();
            if ($detalle_carrito) {
                $carrito->total -= $detalle_carrito->subtotal;
                $carrito->iva = $carrito->total*0.16;
                if($detalle_carrito->delete()){
                    return response()->json([
                        'status'    => 'success',
                        "data"      =>  $carrito,
                        'msg'       => "information successfully registered"
                    ]);
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'msg'       => "product not found"
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'msg'       => "information could not be successfully registered"
                ]);
            }

        }else{
            return response()->json([
                'status'    => 'error',
                'msg'       => "shopping cart not found"
            ]);
        }
    }
    public function changeCantProducto(Request $request){
        $this->validate($request,[
            'producto_id'   => 'required',
            'carrito_id'    => 'required',
            'cantidad'      => 'required',
            'subtotal'      => 'required',
        ]);
        $carrito=carrito::select('*')
                ->where('status_id','=','1')
                ->where('id','=',$request->input('carrito_id'))
                ->first();
        if ($carrito) {
            $producto = productos::select('id')->where('id','=',$request->input('producto_id'))->first();
            if ($producto) {
                $detalle_carrito = new detalle_carrito::select('*')
                    ->where('carrito_id','=',$request->input('carrito_id'))
                    ->where('producto_id','=',$request->input('producto_id'))
                    ->first();

                    $detalle_carrito->cantidad = $request->input('cantidad');
                    $detalle_carrito->subtotal = $request->input('subtotal');

                if($detalle_carrito->save()){
                    return response()->json([
                        'status'    => 'success',
                        "data"      =>  $carrito,
                        'msg'       => "information successfully registered"
                    ]);
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'msg'       => "product not found"
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'msg'       => "information could not be successfully registered"
                ]);
            }

        }else{
            return response()->json([
                'status'    => 'error',
                'msg'       => "shopping cart not found"
            ]);
        }
    }
    public function addProducto(Request $request){
        $this->validate($request,[
            'producto_id'   => 'required',
            'carrito_id'    => 'required',
            'cantidad'      => 'required',
            'subtotal'      => 'required',
        ]);
        $carrito=carrito::select('*')
                ->where('status_id','=','1')
                ->where('id','=',$request->input('carrito_id'))
                ->first();
        if ($carrito) {
            $producto = productos::select('id')->where('id','=',$request->input('producto_id'))->first();
            if ($producto) {
                $detalle_carrito = new detalle_carrito();
                $detalle_carrito->fill([
                    'producto_id'   => $request->input('producto_id'),
                    'carrito_id'    => $request->input('carrito_id'),
                    'cantidad'      => $request->input('cantidad'),
                    'subtotal'      => $request->input('subtotal')
                ]);
                $carrito->total += $request->input('subtotal');
                $carrito->iva = $carrito->total*0.16;
                if($detalle_carrito->save()){
                    $carrito->save();
                    return response()->json([
                        'status'    => 'success',
                        "data"      =>  $carrito,
                        'msg'       => "information successfully registered"
                    ]);
                }else{
                    return response()->json([
                        'status'    => 'error',
                        'msg'       => "product not found"
                    ]);
                }
            }else{
                return response()->json([
                    'status'    => 'error',
                    'msg'       => "information could not be successfully registered"
                ]);
            }

        }else{
            return response()->json([
                'status'    => 'error',
                'msg'       => "shopping cart not found"
            ]);
        }
    }
    public function createCarrito(Request $request){
        $this->validate($request,[
            'users_id' => 'required'
        ]);
        $carrito=carrito::select('*')
                ->where('status_id','<>','1')
                ->where('users_id','=',$request->input('users_id'))
                ->first();
        if(!$carrito){
            $carrito = new carrito();
            $carrito->status_id=1;
            $carrito->total=0;
            $carrito->iva=0;
            $carrito->fecha=new Date();
            $carrito->users_id=$request->input('users_id');
            if($carrito->save()){
                return response()->json([
                    'status'    => 'success',
                    "data"      =>  $carrito,
                    'msg'       => "information successfully registered"
                ]);
            }else{
                return response()->json([
                    'status'    => 'error',
                    'msg'       => "information could not be successfully registered"
                ]);
            }
        }else{
            return response()->json([
                'status'    => 'error',
                'msg'       => "already has an active shopping cart"
            ]);
        };
    }
    public function getCarrito(Request $request){
        $this->validate($request,['id_user' => 'required']);
        $Carrito = carrito::select("*")
            ->join('detalle_carritos', 'carritos.id', '=', 'detalle_carritos.carrito_id')
        ->where('users.id','=',$request->input('id_user'))
        ->get()
        ->toArray();
        if(count($Carrito)>0){

            return response()->json([
                'status'    => 'success',
                'data'      => $Carrito,
                'msg'       => "information successfully"
            ]);
        }else{
            return response()->json([
                'status'    => 'error',
                'msg'       => "information could not be successfully"
            ]);
        }

    }

    public function index(Request $request){
        $dta = array();
        $result = (object) array();
        $status =200;
        if($request->is('api/categorias')){
            $dta = categorias::select(['*'])->where('activo',"=","1")->get()->toArray();
        }else if($request->is('api/productos')){
            $sql = productos::select(['id','nombre', 'descripcion', 'stock', 'precio', 'imagen'])->where('activo',"=","1")->get()->toArray();
            $dataConvert = array();
            foreach ($sql as $key=>$value) {
                $aux                = (object)array();
                $aux->id            = $value["id"];
                $aux->nombre        = $value["nombre"];
                $aux->descripcion   = $value["descripcion"];
                $aux->stock         = $value["stock"];
                $aux->precio        = $value["precio"];
                $aux->imagen        = url(Storage::url("public/productos/".$value['imagen']));
                array_push($dataConvert,$aux);
            }
            $dta = $dataConvert;
        }
        if(count($dta)<=0){
            $status=400;
            $result->error = true;
            $result->message = "Data no Disponible";
        }else{
            $result->error = false;
            $result->message = "Operacion realizada con exito";
            $result->data = $dta;
        }

        return response()->json($result, $status);
    }
    /**
     * Show the form for creating the resource.
     */
    public function create()
    {
        $data = array();
        $data= $this->DescribeTabla('productos');
        $data['url'] = '/intendencia/create/producto';
        $data['file'] = true;
        return view('form',$data);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request)
    {
        $SaveData =false;
        $file = $request->file('imagen');
        $filename  = time()."-".$file->getClientOriginalName();
        Storage::disk('local')->put("public/productos/".trim($filename," \n\r\t\v\0"), File::get($file));
        $productos              = new productos();
        $productos->imagen      = $filename;
        $productos->nombre      = $request->input('nombre');
        $productos->descripcion = $request->input('descripcion');
        $productos->stock       = $request->input('stock');
        $productos->precio      = $request->input('precio');
        $productos->activo      = $request->input('activo');
        $SaveData               = $productos->save();
        if($SaveData){
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
            $dta = productos::select(['id','nombre', 'descripcion', 'stock', 'precio', 'imagen'])->get()->toArray();
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
