@extends('sondealo_administracion.master_admin')

@section('title')
Administración Sondealo
@endsection

@section('css')
  <style>
    .chart-container{
      margin-top: 3rem;
      width: 100%;
      border: 1px solid rgba(0,0,0,0.1);
    }
    .card{
      margin:auto;
    }
  </style>
@endsection

@section('content')
  <div class="card" style="width: 20rem;">
    <div class="card-body">
      <h5 class="card-title text-center" style="font-size:3rem;">{{$conteo}}</h5>
      <h6 class="card-subtitle mb-2 text-muted">Encuestas respondidas hoy</h6>
    </div>
  </div>

  <div class="chart-container">
    @if ($conteo > 0)
    <h4 class="text-center">Las 7 sucursales que más encuestas generan hoy</h4>
      {!! $chart_1->render() !!}
    @endif
  </div>
@endsection

@section('js')
  <script src="{{asset('js/Chart.min.js')}}"></script>
@endsection
