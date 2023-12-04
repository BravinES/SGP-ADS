@extends('adminlte::page')
@section('title', 'Cadastros')

@section('content_header')
    <div class="callout callout-dark">
        <h3><i class="fas fa-edit"></i>Total de Cadastrados</h3>
    </div>

    <div class="row" id="areaTopCards"></div>
@endsection

@section('content')
    <div class="row" id="graficosCadastro"></div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset("/assets/css/cadastros/main.css")}}" />
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2/dist/chart.min.js"></script>
    <script src="{{asset("/assets/js/sun.js")}}"></script>
    <script src="{{asset("/assets/js/cadastro/main.js")}}"></script>
    <script>
        render.topCard({
            elId: 'areaTopCards',
            method: 'GET',
            url: {!! json_encode(route('cadastros.index.api')) !!},
            _token: '{{ csrf_token() }}'
        });

        render.graficosCadastro({
            elId: 'graficosCadastro',
            method: 'GET',
            url: {!! json_encode(route('cadastros.index.api')) !!},
            _token: '{{ csrf_token() }}'
        });

    </script>

@endsection
