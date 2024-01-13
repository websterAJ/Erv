@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @php
                    $heads = array();
                    $dtaFinal = array();

                    $btnEdit = '<button class="btn btn-xs btn-default text-primary" title="Editar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </button>';
                    $btnDelete = '<button class="btn btn-xs btn-default text-danger" title="Eliminar">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>';
                    $btnDetails = '<button class="btn btn-xs btn-default text-teal" title="Detalle">
                                    <i class="fa fa-lg fa-fw fa-eye"></i>
                                </button>';

                    foreach ($data as $item) {
                        $aux = array();
                        foreach ($item as $value) {
                            array_push($aux,$value);
                        }
                        array_push($aux,'<div class="btn-group">'.$btnEdit.$btnDelete.$btnDetails.'</div>');
                        array_push($dtaFinal,$aux);
                    }

                    foreach ($columnas as $value) {
                        array_push($heads,__('adminlte::adminlte.'.$value));
                    }
                    array_push($heads, __('adminlte::adminlte.accion'));

                    $config = [
                        'data' => $dtaFinal,
                        'order' => [[1, 'asc']],
                    ];
                    @endphp
                    <a href="{{$createURL}}" class="btn btn-md btn-primary mb-2" title="Nuevo Registro">
                        <i class="fa fa-lg fa-fw fa-plus"></i>
                    </a>
                    @if (count($data)>0)
                        <x-adminlte-datatable id="table1" :heads="$heads">
                            @foreach($config['data'] as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td>{!! $cell !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </x-adminlte-datatable>
                    @else
                    <div class="text-center">
                        <h4>No se encontraron registros.</h4>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@stop
