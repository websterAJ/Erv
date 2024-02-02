
<div>
    @foreach ($persona['campos'] as $item => $value)
        @if($value->Field != 'id'  && $value->Field != 'created_at' && $value->Field != 'updated_at')
            <label for="{{$value->Field}}">{{__('adminlte::adminlte.'.$value->Field)}}</label>
            @if($value->Type == "text" || $value->Type == "varchar(255)")
                <input type="text" name="{{$value->Field}}" id="{{$value->Field}}" class="form-control {{$errors->has($value->Field) ? 'is-invalid' : ''}}">
            @elseif($value->Type == "integer" || $value->Type == "bigint" || $value->Type == "int" )
                <input type="number" name="{{$value->Field}}" id="{{$value->Field}}" class="form-control {{$errors->has($value->Field) ? 'is-invalid' : ''}}">
            @elseif($value->Type == "decimal(8,2)" || $value->Type == "decimal" || $value->Type == "float" || $value->Type == "numeric" || $value->Type == "double precision" || $value->Type == "double")
                <input type="number" name="{{$value->Field}}" id="{{$value->Field}}" class="form-control {{$errors->has($value->Field) ? 'is-invalid' : ''}}" step="any">
            @elseif($value->Type == "date")
                <input type="date" name="{{$value->Field}}" id="{{$value->Field}}" class="form-control {{$errors->has($value->Field) ? 'is-invalid' : ''}}">
            @elseif ($value->Field == "tipo_personas_id" || $value->Field == 'estatus_id' || $value->Field == 'ascensos_id')
                <select name="{{$value->Field}}" id="{{$value->Field}}" class="form-control {{$errors->has($value->Field) ? 'is-invalid' : ''}}">
                    <option value="0">Seleccionar una opcion</option>
                    @switch($value->Field)
                        @case("tipo_personas_id")
                            @foreach ($persona['tipoPersona'] as $option)
                                <option value="{{$option['id']}}">{{$option['nombre']}}</option>
                            @endforeach
                            @break
                        @case('estatus_id')
                            @foreach ($persona['status'] as $option)
                                <option value="{{$option['id']}}">{{$option['nombre']}}</option>
                            @endforeach
                            @break
                        @case('ascensos_id')
                            @foreach ($persona['ascensos'] as $option)
                                <option value="{{$option['id']}}">{{$option['nombre']}}</option>
                            @endforeach
                            @break
                    @endswitch
                </select>
            @endif
        @endif
    @endforeach
</div>
