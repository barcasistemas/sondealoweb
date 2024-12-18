@extends('master.logged')

@section('css')
  <link rel="stylesheet" href="{{asset('css/home.css')}}"/>
@endsection

@section('title')
  Sondealo
@endsection

@section('content')
  <div class="contenedor-superior">

  {!!$sucursales_html!!}

  @if (Session::has('sucursal_fijada'))
    <div class="jumbotron">
      <h1 class="display-4">Bienvenido(a)</h1>
        <hr class="my-4">
      <p>Ha seleccionado la sucursal {{Session::get('sucursal_fijada')}}</p>
    </div>
  @endif
  <div>
@endsection
