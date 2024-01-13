@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form id="NewRegister" method="POST" {{$file ? 'enctype="multipart/form-data"' : ''}}>
                    <div class="card-body">
                        {{ csrf_field() }}
                        @foreach ($campos as $item)
                            @if ($item->column_name != 'id' && $item->column_name != 'created_at' && $item->column_name != 'updated_at' && $item->column_name != 'estatus_id')
                                <div class="form-group">
                                    @php
                                        $label = "";
                                        switch ($item->column_name) {
                                            case 'cuota_id':
                                                $label= "Cuota";
                                                break;
                                            case 'estatus_id':
                                                $label= "Estatus";
                                                break;
                                            case 'zona_id':
                                                $label= "Zona";
                                                break;
                                            default:
                                                $label= $item->column_name;
                                                break;
                                        }
                                    @endphp
                                    <label for="{{$item->column_name}}">{{__('adminlte::adminlte.'.$label)}}</label>
                                    @if ($item->column_name == "cuota_id" || $item->column_name == 'estatus_id' || $item->column_name == 'zona_id')
                                        <select name="{{$item->column_name}}" id="{{$item->column_name}}" class="form-control {{$errors->has($item->column_name) ? 'is-invalid' : ''}}">
                                            <option value="0">Seleccionar una opcion</option>
                                            @switch($item->column_name)
                                                @case("cuota_id")
                                                    @foreach ($cuota as $option)
                                                        <option value="{{$option['id']}}">{{$option['nombre']." ".$option['monto']." ".$option['fecha']}}</option>
                                                    @endforeach
                                                    @break
                                                @case('estatus_id')
                                                    @foreach ($status as $option)
                                                        <option value="{{$option['id']}}">{{$option['nombre']}}</option>
                                                    @endforeach
                                                    @break
                                                @case('zona_id')
                                                    @foreach ($zonas as $option)
                                                        <option value="{{$option['id']}}">{{$option['nombre']}}</option>
                                                    @endforeach
                                                    @break
                                            @endswitch
                                        </select>
                                    @elseif ($item->data_type == "boolean")
                                        <select name="{{$item->column_name}}" id="{{$item->column_name}}" class="form-control {{$errors->has($item->column_name) ? 'is-invalid' : ''}}">
                                            <option value="">Seleccionar una opcion</option>
                                            <option value="1">Si</option>
                                            <option value="0">No</option>
                                        </select>
                                    @elseif ($item->column_name == "comprobante")
                                        <input type="file" name="{{$item->column_name}}" id="{{$item->column_name}}" class="form-control {{$errors->has($item->column_name) ? 'is-invalid' : ''}}" accept="image/*,.pdf">
                                    @else
                                        @if($item->data_type == "text" || $item->data_type == "character varying")
                                            <input type="text" name="{{$item->column_name}}" id="{{$item->column_name}}" class="form-control {{$errors->has($item->column_name) ? 'is-invalid' : ''}}">
                                        @elseif($item->data_type == "integer" || $item->data_type == "bigint" )
                                            <input type="number" name="{{$item->column_name}}" id="{{$item->column_name}}" class="form-control {{$errors->has($item->column_name) ? 'is-invalid' : ''}}">
                                        @elseif($item->data_type == "decimal" || $item->data_type == "float" || $item->data_type == "numeric" || $item->data_type == "double precision")
                                            <input type="number" name="{{$item->column_name}}" id="{{$item->column_name}}" class="form-control {{$errors->has($item->column_name) ? 'is-invalid' : ''}}" step="any">
                                        @elseif($item->data_type == "date")
                                            <input type="date" name="{{$item->column_name}}" id="{{$item->column_name}}" class="form-control {{$errors->has($item->column_name) ? 'is-invalid' : ''}}">
                                        @endif
                                    @endif

                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-primary" type="button" id="enviar">Enviar</button>
                        <button class="btn btn-secondary" type="reset">Limpiar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#enviar").on("click", function(e){
            e.preventDefault();
            var form = new FormData($("#NewRegister")[0]);
            $.ajax({
                type: "post",
                url: "{{$url}}",
                data: form,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function (resp) {
                    if(resp.status == 'success'){
                        Swal.fire({
                            title: "Registro Exitoso!",
                            text: resp.msg,
                            icon: "success"
                        });
                        window.location.replace("/home");
                    }else{
                        Swal.fire({
                            title: "Registro Exitoso!",
                            text: resp.msg,
                            icon: "success"
                        });
                    }
                }
            });
        });
    </script>
@stop
