@extends('master.logged')

@section('title')
  Método de pago
@endsection

@section('css')
  <link rel="stylesheet" href="{{asset('css/metodo_pago.css')}}"/>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Método de Pago</li>
  </ol>
</nav>
  <iframe id="frame-payment" scrolling="no" onload="resizeIframe(this);"  src="https://sondealo.com/renovar/{{Session::get('id')}}-{{$plan_id}}-4658767">
  </iframe>
@endsection

@section('js')
  <script type="text/javascript">
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
  </script>

@endsection
