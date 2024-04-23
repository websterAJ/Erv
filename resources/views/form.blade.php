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
                        @foreach ($campos as $key => $value)
                            @if ($campos[$key]->Field != 'id' && $campos[$key]->Field != 'created_at' && $campos[$key]->Field != 'updated_at' && $campos[$key]->Field != 'estatus_id' && $campos[$key]->Field != 'remember_token')
                                <div class="form-group">
                                    @php
                                        $label = "";
                                        switch ($campos[$key]->Field) {
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
                                                $label= $campos[$key]->Field;
                                                break;
                                        }
                                    @endphp
                                    <label for="{{$campos[$key]->Field}}">{{__('adminlte::adminlte.'.$label)}}</label>
                                    @if ($campos[$key]->Field == "categoria_id" ||$campos[$key]->Field == "cuota_id" || $campos[$key]->Field == 'estatus_id' || $campos[$key]->Field == 'zona_id' || $campos[$key]->Field == 'cargos_id')
                                        <select name="{{$campos[$key]->Field}}" id="{{$campos[$key]->Field}}" class="form-control {{$errors->has($campos[$key]->Field) ? 'is-invalid' : ''}}">
                                            <option value="0">Seleccionar una opcion</option>
                                            @switch($campos[$key]->Field)
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
                                                @case('cargos_id')
                                                    @foreach ($cargos as $option)
                                                        <option value="{{$option['id']}}">{{$option['nombre']}}</option>
                                                    @endforeach
                                                    @break
                                                @case('zona_id')
                                                    @foreach ($zonas as $option)
                                                        <option value="{{$option['id']}}">{{$option['nombre']}}</option>
                                                    @endforeach
                                                    @break
                                                @case('categoria_id')
                                                    @foreach ($categorias as $option)
                                                        <option value="{{$option['id']}}">{{$option['nombre']}}</option>
                                                    @endforeach
                                                    @break
                                            @endswitch
                                        </select>
                                    @elseif ($campos[$key]->Type == "boolean" || $campos[$key]->Type == "tinyint(1)")
                                        <select name="{{$campos[$key]->Field}}" id="{{$campos[$key]->Field}}" class="form-control {{$errors->has($campos[$key]->Field) ? 'is-invalid' : ''}}">
                                            <option value="">Seleccionar una opcion</option>
                                            <option value="1">Si</option>
                                            <option value="0">No</option>
                                        </select>
                                    @elseif ($campos[$key]->Field == "contenido" || $campos[$key]->Field == "resumen")
                                        @php
                                            $config = [
                                                "height" => "250",
                                                "toolbar" => [
                                                    // [groupName, [list of button]]
                                                    ['style', ['bold', 'italic', 'underline', 'clear']],
                                                    ['font', ['strikethrough', 'superscript', 'subscript']],
                                                    ['fontsize', ['fontsize']],
                                                    ['color', ['color']],
                                                    ['para', ['ul', 'ol', 'paragraph']],
                                                    ['height', ['height']],
                                                    ['table', ['table']],
                                                    ['insert', ['link', 'picture']],
                                                    ['view', ['fullscreen', 'help']],
                                                ],
                                            ]
                                        @endphp
                                        <x-adminlte-text-editor name="{{$campos[$key]->Field}}" id="{{$campos[$key]->Field}}" label-class="text-danger"
                                        igroup-size="sm" :config="$config"/>
                                    @elseif ($campos[$key]->Field == "comprobante" || $campos[$key]->Field == "imagen" || $campos[$key]->Field == "flayer")
                                        <input type="file" name="{{$campos[$key]->Field}}" id="{{$campos[$key]->Field}}" class="form-control {{$errors->has($campos[$key]->Field) ? 'is-invalid' : ''}}" accept="image/*,.pdf">
                                    @else
                                        @if($campos[$key]->Type == "text" || $campos[$key]->Type == "varchar(255)")
                                            <input type="text" name="{{$campos[$key]->Field}}" id="{{$campos[$key]->Field}}" class="form-control {{$errors->has($campos[$key]->Field) ? 'is-invalid' : ''}}">
                                        @elseif($campos[$key]->Type == "integer" || $campos[$key]->Type == "bigint" || $campos[$key]->Type == "int" )
                                            <input type="number" name="{{$campos[$key]->Field}}" id="{{$campos[$key]->Field}}" class="form-control {{$errors->has($campos[$key]->Field) ? 'is-invalid' : ''}}">
                                        @elseif($campos[$key]->Type == "decimal(8,2)" || $campos[$key]->Type == "decimal" || $campos[$key]->Type == "float" || $campos[$key]->Type == "numeric" || $campos[$key]->Type == "double precision" || $campos[$key]->Type == "double")
                                            <input type="number" name="{{$campos[$key]->Field}}" id="{{$campos[$key]->Field}}" class="form-control {{$errors->has($campos[$key]->Field) ? 'is-invalid' : ''}}" step="any">
                                        @elseif($campos[$key]->Type == "date")
                                            <input type="date" name="{{$campos[$key]->Field}}" id="{{$campos[$key]->Field}}" class="form-control {{$errors->has($campos[$key]->Field) ? 'is-invalid' : ''}}">
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
